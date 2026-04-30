<?php

namespace App\Http\Controllers;

use App\DTO\AssessmentDTO\AssessmentDTO;
use App\DTO\AuthDTO\LoginDTO;
use App\DTO\AuthDTO\RegisterDTO;
use App\Models\Pengumpulan;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_pt' => 'required|string|max:255',
                'jenis_pt' => 'required|string|max:100',
                'nama_pic' => 'required|string|max:255',
                'no_hp_pic' => 'required|string|max:20',
                'jabatan_pic' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $dto = new RegisterDTO($validated);
            $user = $this->userService->register($dto);

            return $this->successResponse($user, 'Registrasi berhasil. Silakan login untuk melanjutkan.', 201);
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
            return $this->errorResponse($th->getMessage(), $code);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $dto = new LoginDTO($validated);
            $result = $this->userService->login($dto);

            // Determine redirect path based on user status
            $user = $result['user'];
            $pengumpulan = Pengumpulan::where('user_id', $user->id)->first();
            
            $redirectTo = '/verifikasi'; // Default: needs verification
            if ($pengumpulan && in_array($pengumpulan->status, ['IN_PROGRESS', 'SUBMITTED', 'GRADED'])) {
                $redirectTo = '/dashboard';
            }

            // Include user role for reviewer redirect
            if ($user->role === 'REVIEWER') {
                $redirectTo = '/reviewer';
            }

            return $this->successResponse([
                'user' => $user,
                'token' => $result['token'],
                'redirect_to' => $redirectTo,
                'pengumpulan_status' => $pengumpulan?->status,
            ], 'Login berhasil.', 200);
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 401;
            return $this->errorResponse($th->getMessage(), $code);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $this->userService->logout($user);
            }
            return $this->successResponse(null, 'Logout berhasil.', 200);
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
            return $this->errorResponse($th->getMessage(), $code);
        }
    }

    /**
     * GET /api/auth/me — Return current user info + pengumpulan status
     * Used by frontend to determine redirect after login
     */
    public function me(Request $request)
    {
        try {
            $userId = self::getAuthPeserta();
            $user = User::find($userId);

            if (!$user) {
                return $this->errorResponse('User tidak ditemukan.', 404);
            }

            $activePeriodSetting = \App\Models\PengaturanCms::where('key', 'active_period')->first();
            $activePeriod = $activePeriodSetting ? $activePeriodSetting->value : date('Y');

            $pengumpulan = Pengumpulan::where('user_id', $user->id)
                ->where('tahun_periode', $activePeriod)
                ->with(['institusi', 'identitas.agamas'])
                ->first();

            if (!$pengumpulan) {
                // Find previous pengumpulan
                $prevPengumpulan = Pengumpulan::where('user_id', $user->id)
                    ->orderBy('tahun_periode', 'desc')
                    ->first();
                
                if ($prevPengumpulan) {
                    // Create new pengumpulan bypassing verification
                    $pengumpulan = Pengumpulan::create([
                        'user_id' => $user->id,
                        'institution_id' => $prevPengumpulan->institution_id,
                        'tahun_periode' => $activePeriod,
                        'status' => 'IN_PROGRESS', // bypass verifikasi
                        'nama_pic' => $prevPengumpulan->nama_pic,
                        'jabatan_pic' => $prevPengumpulan->jabatan_pic,
                        'no_hp_pic' => $prevPengumpulan->no_hp_pic,
                        'email_pic' => $prevPengumpulan->email_pic,
                        'surat_pernyataan' => $prevPengumpulan->surat_pernyataan,
                        'sk_pendirian' => $prevPengumpulan->sk_pendirian,
                        'sk_akreditasi' => $prevPengumpulan->sk_akreditasi,
                        'profil_pt' => $prevPengumpulan->profil_pt,
                        'struktur_organisasi' => $prevPengumpulan->struktur_organisasi,
                        'sk_tim' => $prevPengumpulan->sk_tim,
                    ]);

                    // Copy Identitas
                    $prevIdentitas = \App\Models\Identitas::where('pengumpulan_id', $prevPengumpulan->id)->first();
                    if ($prevIdentitas) {
                        $newIdentitas = $prevIdentitas->replicate();
                        $newIdentitas->pengumpulan_id = $pengumpulan->id;
                        $newIdentitas->save();
                        
                        // Copy Agama
                        foreach (\App\Models\Agama::where('identitas_id', $prevIdentitas->id)->get() as $agama) {
                            $newAgama = $agama->replicate();
                            $newAgama->identitas_id = $newIdentitas->id;
                            $newAgama->save();
                        }
                    }
                    $pengumpulan->load('institusi');
                } else {
                    $pengumpulan = Pengumpulan::where('user_id', $user->id)->with(['institusi'])->first();
                }
            }
            
            $editSetting = \App\Models\PengaturanCms::where('key', 'is_peserta_edit_enabled')->first();
            $isEditEnabled = $editSetting ? filter_var($editSetting->value, FILTER_VALIDATE_BOOLEAN) : true;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                ],
                'pengumpulan' => $pengumpulan ? [
                    'id' => $pengumpulan->id,
                    'status' => $pengumpulan->status,
                    'tahun_periode' => $pengumpulan->tahun_periode,
                    'nama_pic' => $pengumpulan->nama_pic,
                    'jabatan_pic' => $pengumpulan->jabatan_pic,
                    'no_hp_pic' => $pengumpulan->no_hp_pic,
                    'email_pic' => $user->email,
                    'institusi' => $pengumpulan->institusi,
                    'identitas' => $pengumpulan->identitas,
                    'agamas' => $pengumpulan->identitas?->agamas,
                ] : null,
                'is_edit_enabled' => $isEditEnabled,
                'active_period' => $activePeriod,
            ], 'Data user berhasil diambil.', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    /**
     * Helper: Get authenticated reviewer ID.
     * Falls back to hardcoded ID 8 for development/plotting sementara.
     */
    public static function getAuthReviewer()
    {
        return Auth::id();
    }

    /**
     * Helper: Get authenticated peserta ID.
     * Falls back to hardcoded ID 3 for development/plotting sementara.
     */
    public static function getAuthPeserta()
    {
        return Auth::id();
    }

    public function getAuth()
    {
        return self::getAuthReviewer();
    }

    /**
     * Helper internal untuk mem-build DTO dengan konteks User Auth yang aman.
     * Mencegah celah IDOR (Insecure Direct Object Reference).
     */
    public function getAuthDTO(): AssessmentDTO
    {
        $userId = $this->getAuth();
        return new AssessmentDTO($userId);
    }
}

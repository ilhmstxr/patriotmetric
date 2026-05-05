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
            $pengumpulan = Pengumpulan::where('user_id', $user->id)->with('institusi')->first();
            
            $redirectTo = '/verifikasi'; // Default: needs verification
            if ($pengumpulan && in_array($pengumpulan->status, ['IN_PROGRESS', 'SUBMITTED', 'GRADED'])) {
                $redirectTo = '/dashboard';
            }

            // Include user role for reviewer redirect
            if ($user->role === 'REVIEWER') {
                $redirectTo = '/reviewer';
            }

            return $this->successResponse([
                'user' => array_merge($user->toArray(), [
                    'nama_institusi' => $pengumpulan?->institusi?->nama_institusi
                ]),
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

            $profileEditSetting = \App\Models\PengaturanCms::where('key', 'is_peserta_profile_edit_enabled')->first();
            $isProfileEditEnabled = $profileEditSetting ? filter_var($profileEditSetting->value, FILTER_VALIDATE_BOOLEAN) : true;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'nama_institusi' => $pengumpulan?->institusi?->nama_institusi,
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
                'is_peserta_profile_edit_enabled' => $isProfileEditEnabled,
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
     * PUT /api/auth/profile
     * Update seluruh data profil peserta: data PIC, identitas (visi, misi, data numerik), dan demografi agama.
     * Hanya dapat dilakukan jika CMS setting 'is_peserta_profile_edit_enabled' bernilai true.
     */
    public function updateProfile(Request $request)
    {
        try {
            // Cek permission dari CMS
            $profileEditSetting   = \App\Models\PengaturanCms::where('key', 'is_peserta_profile_edit_enabled')->first();
            $isProfileEditEnabled = $profileEditSetting ? filter_var($profileEditSetting->value, FILTER_VALIDATE_BOOLEAN) : true;

            if (!$isProfileEditEnabled) {
                return $this->errorResponse('Fitur edit profil saat ini dinonaktifkan oleh admin.', 403);
            }

            $userId = self::getAuthPeserta();
            if (!$userId) {
                return $this->errorResponse('Unauthorized.', 401);
            }

            $validated = $request->validate([
                'nama_pic'      => 'required|string|max:255',
                'jabatan_pic'   => 'nullable|string|max:100',
                'no_hp_pic'     => 'required|string|max:20',
                'visi'          => 'nullable|string',
                'misi'          => 'nullable|string',
                'jml_fakultas'  => 'nullable|integer|min:0',
                'jml_prodi'     => 'nullable|integer|min:0',
                'jml_dosen'     => 'nullable|integer|min:0',
                'jml_tendik'    => 'nullable|integer|min:0',
                'jml_mahasiswa' => 'nullable|integer|min:0',
                'jml_ormawa'    => 'nullable|integer|min:0',
                'jml_ukm'       => 'nullable|integer|min:0',
                'agamas'        => 'nullable|array',
            ]);

            // Ambil pengumpulan aktif milik peserta
            $activePeriodSetting = \App\Models\PengaturanCms::where('key', 'active_period')->first();
            $activePeriod        = $activePeriodSetting ? $activePeriodSetting->value : date('Y');

            $pengumpulan = Pengumpulan::where('user_id', $userId)
                ->where('tahun_periode', $activePeriod)
                ->with(['identitas.agamas'])
                ->first();

            if (!$pengumpulan) {
                return $this->errorResponse('Data pengumpulan tidak ditemukan.', 404);
            }

            // 1. Update data PIC di pengumpulan
            $pengumpulan->update([
                'nama_pic'    => $validated['nama_pic'],
                'jabatan_pic' => $validated['jabatan_pic'] ?? $pengumpulan->jabatan_pic,
                'no_hp_pic'   => $validated['no_hp_pic'],
            ]);

            // 2. Update data Identitas
            if ($pengumpulan->identitas) {
                $pengumpulan->identitas->update([
                    'visi'          => $validated['visi']          ?? $pengumpulan->identitas->visi,
                    'misi'          => $validated['misi']          ?? $pengumpulan->identitas->misi,
                    'jml_fakultas'  => $validated['jml_fakultas']  ?? $pengumpulan->identitas->jml_fakultas,
                    'jml_prodi'     => $validated['jml_prodi']     ?? $pengumpulan->identitas->jml_prodi,
                    'jml_dosen'     => $validated['jml_dosen']     ?? $pengumpulan->identitas->jml_dosen,
                    'jml_tendik'    => $validated['jml_tendik']    ?? $pengumpulan->identitas->jml_tendik,
                    'jml_mahasiswa' => $validated['jml_mahasiswa'] ?? $pengumpulan->identitas->jml_mahasiswa,
                    'jml_ormawa'    => $validated['jml_ormawa']    ?? $pengumpulan->identitas->jml_ormawa,
                    'jml_ukm'       => $validated['jml_ukm']       ?? $pengumpulan->identitas->jml_ukm,
                ]);

                // 3. Update demografi agama (upsert per agama)
                if (!empty($validated['agamas']) && is_array($validated['agamas'])) {
                    foreach ($validated['agamas'] as $agamaKey => $jumlah) {
                        \App\Models\Agama::updateOrCreate(
                            ['identitas_id' => $pengumpulan->identitas->id, 'agama' => $agamaKey],
                            ['jumlah' => (int) ($jumlah ?: 0)]
                        );
                    }
                }
            }

            return $this->successResponse([
                'nama_pic'    => $pengumpulan->fresh()->nama_pic,
                'jabatan_pic' => $pengumpulan->fresh()->jabatan_pic,
                'no_hp_pic'   => $pengumpulan->fresh()->no_hp_pic,
            ], 'Profil berhasil diperbarui.', 200);
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
            return $this->errorResponse($th->getMessage(), $code);
        }
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

<?php

namespace App\Http\Controllers;

use App\DTO\AssessmentDTO\AssessmentDTO;
use App\DTO\AuthDTO\LoginDTO;
use App\DTO\AuthDTO\RegisterDTO;
use App\Models\Institusi;
use App\Models\Assessment;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
                'email' => ['required', 'email', 'regex:/@[a-z0-9.-]+\.ac\.id$/i', 'unique:users,email'],
                'password' => 'required|string|min:8|confirmed',
            ], [
                'email.regex' => 'Email harus menggunakan domain institusi resmi (.ac.id).',
                'email.unique' => 'Email ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            ]);

            // 1 instansi 1 akun: cek nama_institusi & domain email
            $domain = strtolower(substr(strrchr($validated['email'], '@'), 1));

            $existsByName = Institusi::whereRaw('LOWER(nama_institusi) = ?', [strtolower($validated['nama_pt'])])->exists();
            if ($existsByName) {
                return $this->errorResponse('Institusi dengan nama tersebut sudah memiliki akun terdaftar. Silakan masuk atau hubungi admin.', 422);
            }

            $existsByDomain = Institusi::where('domain_email', $domain)->exists();
            if ($existsByDomain) {
                return $this->errorResponse('Domain email institusi ini sudah memiliki akun terdaftar. Satu institusi hanya boleh memiliki satu akun.', 422);
            }

            $dto = new RegisterDTO($validated);
            $user = $this->userService->register($dto);

            return $this->successResponse($user, 'Registrasi berhasil. Silakan login untuk melanjutkan.', 201);
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
            return $this->errorResponse($th->getMessage(), $code);
        }
    }

    /**
     * GET /api/auth/check-institusi?nama=...&email=...
     * Public endpoint untuk cek ketersediaan saat user mengisi form daftar.
     */
    public function checkInstitusi(Request $request)
    {
        $nama = trim((string) $request->query('nama', ''));
        $email = trim((string) $request->query('email', ''));

        $reasons = [];

        if ($nama !== '') {
            $existsByName = Institusi::whereRaw('LOWER(nama_institusi) = ?', [strtolower($nama)])->exists();
            if ($existsByName) $reasons[] = 'Nama institusi sudah terdaftar.';
        }

        return $this->successResponse([
            'exists' => count($reasons) > 0,
            'message' => count($reasons) > 0 ? implode(' ', $reasons) : 'Tersedia.',
        ], 'OK', 200);
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $dto = new LoginDTO($validated);
            $result = $this->userService->login($dto, false);

            // Determine redirect path based on user status
            $user = $result['user'];
            $Assessment = Assessment::where('user_id', $user->id)->with('institusi')->first();
            
            $redirectTo = '/verifikasi'; // Default: needs verification
            if ($Assessment && in_array($Assessment->status, ['IN_PROGRESS', 'SUBMITTED', 'GRADED'])) {
                $redirectTo = '/dashboard';
            }

            // Include user role for reviewer redirect
            if ($user->role === 'REVIEWER') {
                $redirectTo = '/reviewer';
            }

            return $this->successResponse([
                'user' => array_merge($user->toArray(), [
                    'nama_institusi' => $Assessment?->institusi?->nama_institusi
                ]),
                'token' => $result['token'],
                'token_expires_at' => $result['expires_at'] ?? null,
                'redirect_to' => $redirectTo,
                'Assessment_status' => $Assessment?->status,
            ], 'Login berhasil.', 200);
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 401;
            return $this->errorResponse($th->getMessage(), $code);
        }
    }

    /**
     * POST /api/auth/change-password
     * Mengubah kata sandi user yang sedang login. Token aktif lain dihapus
     * (single-session policy tetap dijaga: token saat ini dipertahankan).
     */
    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ], [
                'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
                'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            ]);

            $user = $request->user();
            if (!$user) return $this->errorResponse('Unauthorized.', 401);

            if (!Hash::check($validated['current_password'], $user->password)) {
                return $this->errorResponse('Kata sandi lama tidak sesuai.', 422);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            // Cabut token lain (selain yang aktif sekarang) untuk keamanan.
            $currentTokenId = optional($request->user()->currentAccessToken())->id;
            if ($currentTokenId) {
                $user->tokens()->where('id', '!=', $currentTokenId)->delete();
            }

            return $this->successResponse(null, 'Kata sandi berhasil diperbarui.', 200);
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
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
     * GET /api/auth/me — Return current user info + Assessment status
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

            $Assessment = Assessment::where('user_id', $user->id)
                ->where('tahun_periode', $activePeriod)
                ->with(['institusi', 'identitas.agamas'])
                ->first();

            if (!$Assessment) {
                // Find previous Assessment
                $prevAssessment = Assessment::where('user_id', $user->id)
                    ->orderBy('tahun_periode', 'desc')
                    ->first();
                
                if ($prevAssessment) {
                    // Create new Assessment bypassing verification
                    $Assessment = Assessment::create([
                        'user_id' => $user->id,
                        'institution_id' => $prevAssessment->institution_id,
                        'tahun_periode' => $activePeriod,
                        'status' => 'IN_PROGRESS', // bypass verifikasi
                        'nama_pic' => $prevAssessment->nama_pic,
                        'jabatan_pic' => $prevAssessment->jabatan_pic,
                        'no_hp_pic' => $prevAssessment->no_hp_pic,
                        'email_pic' => $prevAssessment->email_pic,
                        'surat_pernyataan' => $prevAssessment->surat_pernyataan,
                        'sk_pendirian' => $prevAssessment->sk_pendirian,
                        'sk_akreditasi' => $prevAssessment->sk_akreditasi,
                        'profil_pt' => $prevAssessment->profil_pt,
                        'struktur_organisasi' => $prevAssessment->struktur_organisasi,
                        'sk_tim' => $prevAssessment->sk_tim,
                    ]);

                    // Copy Identitas
                    $prevIdentitas = \App\Models\Identitas::where('Assessment_id', $prevAssessment->id)->first();
                    if ($prevIdentitas) {
                        $newIdentitas = $prevIdentitas->replicate();
                        $newIdentitas->Assessment_id = $Assessment->id;
                        $newIdentitas->save();
                        
                        // Copy Agama
                        foreach (\App\Models\Agama::where('identitas_id', $prevIdentitas->id)->get() as $agama) {
                            $newAgama = $agama->replicate();
                            $newAgama->identitas_id = $newIdentitas->id;
                            $newAgama->save();
                        }
                    }
                    $Assessment->load(['institusi', 'identitas.agamas']);
                } else {
                    $Assessment = Assessment::where('user_id', $user->id)->with(['institusi', 'identitas.agamas'])->first();
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
                    'nama_institusi' => $Assessment?->institusi?->nama_institusi,
                ],
                'Assessment' => $Assessment ? [
                    'id' => $Assessment->id,
                    'status' => $Assessment->status,
                    'tahun_periode' => $Assessment->tahun_periode,
                    'nama_pic' => $Assessment->nama_pic,
                    'jabatan_pic' => $Assessment->jabatan_pic,
                    'no_hp_pic' => $Assessment->no_hp_pic,
                    'email_pic' => $user->email,
                    'institusi' => $Assessment->institusi,
                    'identitas' => $Assessment->identitas,
                    'agamas' => $Assessment->identitas?->agamas,
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
                'email'         => ['nullable', 'email', 'regex:/@[a-z0-9.-]+\.ac\.id$/i', Rule::unique('users', 'email')->ignore($userId)],
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
            ], [
                'email.regex' => 'Email harus menggunakan domain institusi resmi (.ac.id).',
                'email.unique' => 'Email tersebut sudah digunakan akun lain.',
            ]);

            // Ambil Assessment aktif milik peserta
            $activePeriodSetting = \App\Models\PengaturanCms::where('key', 'active_period')->first();
            $activePeriod        = $activePeriodSetting ? $activePeriodSetting->value : date('Y');

            $Assessment = Assessment::where('user_id', $userId)
                ->where('tahun_periode', $activePeriod)
                ->with(['identitas.agamas'])
                ->first();

            if (!$Assessment) {
                return $this->errorResponse('Data Assessment tidak ditemukan.', 404);
            }

            // 1. Update data PIC di Assessment
            $Assessment->update([
                'nama_pic'    => $validated['nama_pic'],
                'jabatan_pic' => $validated['jabatan_pic'] ?? $Assessment->jabatan_pic,
                'no_hp_pic'   => $validated['no_hp_pic'],
            ]);

            // 1b. Update email user (akun) jika dikirim & berbeda
            if (!empty($validated['email'])) {
                $user = User::find($userId);
                if ($user && $user->email !== $validated['email']) {
                    $user->email = $validated['email'];
                    $user->save();
                }
            }

            // 2. Update data Identitas
            if ($Assessment->identitas) {
                $Assessment->identitas->update([
                    'visi'          => $validated['visi']          ?? $Assessment->identitas->visi,
                    'misi'          => $validated['misi']          ?? $Assessment->identitas->misi,
                    'jml_fakultas'  => $validated['jml_fakultas']  ?? $Assessment->identitas->jml_fakultas,
                    'jml_prodi'     => $validated['jml_prodi']     ?? $Assessment->identitas->jml_prodi,
                    'jml_dosen'     => $validated['jml_dosen']     ?? $Assessment->identitas->jml_dosen,
                    'jml_tendik'    => $validated['jml_tendik']    ?? $Assessment->identitas->jml_tendik,
                    'jml_mahasiswa' => $validated['jml_mahasiswa'] ?? $Assessment->identitas->jml_mahasiswa,
                    'jml_ormawa'    => $validated['jml_ormawa']    ?? $Assessment->identitas->jml_ormawa,
                    'jml_ukm'       => $validated['jml_ukm']       ?? $Assessment->identitas->jml_ukm,
                ]);

                // 3. Update demografi agama (upsert per agama)
                if (!empty($validated['agamas']) && is_array($validated['agamas'])) {
                    foreach ($validated['agamas'] as $agamaKey => $jumlah) {
                        \App\Models\Agama::updateOrCreate(
                            ['identitas_id' => $Assessment->identitas->id, 'agama' => $agamaKey],
                            ['jumlah' => (int) ($jumlah ?: 0)]
                        );
                    }
                }
            }

            return $this->successResponse([
                'nama_pic'    => $Assessment->fresh()->nama_pic,
                'jabatan_pic' => $Assessment->fresh()->jabatan_pic,
                'no_hp_pic'   => $Assessment->fresh()->no_hp_pic,
                'email'       => User::find($userId)?->email,
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

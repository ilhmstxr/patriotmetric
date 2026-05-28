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
    protected $assessmentRepository;
    protected $institusiRepository;
    protected $cmsRepository;
    protected $userRepository;

    public function __construct(
        UserService $userService,
        \App\Repositories\AssessmentRepository $assessmentRepository,
        \App\Repositories\InstitusiRepository $institusiRepository,
        \App\Repositories\PengaturanCmsRepository $cmsRepository,
        \App\Repositories\UserRepository $userRepository
    ) {
        $this->userService = $userService;
        $this->assessmentRepository = $assessmentRepository;
        $this->institusiRepository = $institusiRepository;
        $this->cmsRepository = $cmsRepository;
        $this->userRepository = $userRepository;
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

            if ($this->institusiRepository->existsByName($validated['nama_pt'])) {
                return $this->errorResponse('Institusi dengan nama tersebut sudah memiliki akun terdaftar. Silakan masuk atau hubungi admin.', 422);
            }

            if ($this->institusiRepository->existsByDomain($domain)) {
                return $this->errorResponse('Domain email institusi ini sudah memiliki akun terdaftar. Satu institusi hanya boleh memiliki satu akun.', 422);
            }

            $dto = new RegisterDTO($validated);
            $user = $this->userService->register($dto);

            // Create Sanctum token for auto-login after registration
            $token = $user->createToken('auth_token', ['*'], now()->addHours(8))->plainTextToken;

            return $this->successResponse([
                'user' => $user,
                'token' => $token,
                'user_status' => 'UNVERIFIED',
                'assessment_status' => 'UNVERIFIED',
                'redirect_to' => '/cek-email',
            ], 'Registrasi berhasil. Silakan verifikasi email Anda.', 201);
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
            if ($this->institusiRepository->existsByName($nama)) {
                 $reasons[] = 'Nama institusi sudah terdaftar.';
            }
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

            // Determine redirect path based on user status and assessment status
            $user = $result['user'];
            $assessment = $this->assessmentRepository->findActiveAssessmentByUserId($user->id);

            $redirectTo = '/dashboard';
            if ($user->status === 'UNVERIFIED') {
                $redirectTo = '/cek-email';
            } elseif ($assessment && $assessment->status === 'UNVERIFIED') {
                $redirectTo = '/verifikasi';
            }

            // Include user role for reviewer redirect
            if ($user->role === 'REVIEWER') {
                $redirectTo = '/reviewer';
            }

            return $this->successResponse([
                'user' => array_merge($user->toArray(), [
                    'nama_institusi' => $assessment?->institusi?->nama_institusi
                ]),
                'token' => $result['token'],
                'token_expires_at' => $result['expires_at'] ?? null,
                'redirect_to' => $redirectTo,
                'user_status' => $user->status,
                'assessment_status' => $assessment?->status,
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
            $user = $this->userRepository->find($userId);

            if (!$user) {
                return $this->errorResponse('User tidak ditemukan.', 404);
            }

            $activePeriodSetting = $this->cmsRepository->getByKey('active_period');
            $activePeriod = $activePeriodSetting ? $activePeriodSetting->value : date('Y');

            $assessment = $this->assessmentRepository->findActiveAssessmentByUserIdAndYear($user->id, $activePeriod);

            if (!$assessment) {
                // Find previous Assessment
                $prevAssessment = $this->assessmentRepository->findLatestAssessmentByUserId($user->id);
                
                if ($prevAssessment) {
                    // Create new Assessment bypassing verification
                    $assessment = $this->assessmentRepository->create([
                        'user_id' => $user->id,
                        'institution_id' => $prevAssessment->institution_id,
                        'tahun_periode' => $activePeriod,
                        'status' => 'ACTIVE',
                        'nama_pic' => $prevAssessment->nama_pic,
                        'jabatan_pic' => $prevAssessment->jabatan_pic,
                        'no_hp_pic' => $prevAssessment->no_hp_pic,
                    ]);

                    // Copy Identitas
                    $prevIdentitas = $this->assessmentRepository->findIdentitasByAssessmentId($prevAssessment->id);
                    if ($prevIdentitas) {
                        $identitasData = [
                            'Assessment_id' => $assessment->id,
                            'visi' => $prevIdentitas->visi,
                            'misi' => $prevIdentitas->misi,
                            'jml_mahasiswa' => $prevIdentitas->jml_mahasiswa,
                            'jml_dosen' => $prevIdentitas->jml_dosen,
                            'jml_tendik' => $prevIdentitas->jml_tendik,
                            'jml_prodi' => $prevIdentitas->jml_prodi,
                            'jml_fakultas' => $prevIdentitas->jml_fakultas,
                            'jml_ukm' => $prevIdentitas->jml_ukm,
                            'jml_ormawa' => $prevIdentitas->jml_ormawa,
                            'legal_documents' => $prevIdentitas->legal_documents,
                            'is_verified' => $prevIdentitas->is_verified,
                        ];
                        $newIdentitas = $this->assessmentRepository->upsertIdentitas($assessment->id, $identitasData);
                        
                        // Copy Agama
                        $agamas = $this->assessmentRepository->getAgamasByIdentitasId($prevIdentitas->id);
                        foreach ($agamas as $agama) {
                            $this->assessmentRepository->upsertAgama($newIdentitas->id, $agama->agama, $agama->jumlah);
                        }
                    }
                    $assessment = $this->assessmentRepository->findActiveAssessmentByUserIdAndYear($user->id, $activePeriod);
                } else {
                    $assessment = $this->assessmentRepository->findActiveAssessmentByUserId($user->id);
                }
            }
            
            $editSetting = $this->cmsRepository->getByKey('is_peserta_edit_enabled');
            $isEditEnabled = $editSetting ? filter_var($editSetting->value, FILTER_VALIDATE_BOOLEAN) : true;

            $profileEditSetting = $this->cmsRepository->getByKey('is_peserta_profile_edit_enabled');
            $isProfileEditEnabled = $profileEditSetting ? filter_var($profileEditSetting->value, FILTER_VALIDATE_BOOLEAN) : true;

            return $this->successResponse([
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'nama_institusi' => $assessment?->institusi?->nama_institusi,
                ],
                'assessment' => $assessment ? [
                    'id' => $assessment->id,
                    'status' => $assessment->status,
                    'tahun_periode' => $assessment->tahun_periode,
                    'nama_pic' => $assessment->nama_pic,
                    'jabatan_pic' => $assessment->jabatan_pic,
                    'no_hp_pic' => $assessment->no_hp_pic,
                    'email_pic' => $user->email,
                    'institusi' => $assessment->institusi,
                    'identitas' => $assessment->identitas,
                    'agamas' => $assessment->identitas?->agamas,
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
     */
    public static function getAuthReviewer()
    {
        return Auth::id();
    }

    /**
     * Helper: Get authenticated peserta ID.
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
            $profileEditSetting = $this->cmsRepository->getByKey('is_peserta_profile_edit_enabled');
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
            $activePeriodSetting = $this->cmsRepository->getByKey('active_period');
            $activePeriod = $activePeriodSetting ? $activePeriodSetting->value : date('Y');

            $assessment = $this->assessmentRepository->findActiveAssessmentByUserIdAndYear($userId, $activePeriod);

            if (!$assessment) {
                return $this->errorResponse('Data Assessment tidak ditemukan.', 404);
            }

            // 1. Update data PIC di Assessment
            $this->assessmentRepository->update($assessment->id, [
                'nama_pic'    => $validated['nama_pic'],
                'jabatan_pic' => $validated['jabatan_pic'] ?? $assessment->jabatan_pic,
                'no_hp_pic'   => $validated['no_hp_pic'],
            ]);

            // 1b. Update email user (akun) jika dikirim & berbeda
            if (!empty($validated['email'])) {
                $user = $this->userRepository->find($userId);
                if ($user && $user->email !== $validated['email']) {
                    $this->userRepository->update($userId, ['email' => $validated['email']]);
                }
            }

            // 2. Update data Identitas
            $identitas = $this->assessmentRepository->findIdentitasByAssessmentId($assessment->id);
            if ($identitas) {
                $this->assessmentRepository->upsertIdentitas($assessment->id, [
                    'visi'          => $validated['visi']          ?? $identitas->visi,
                    'misi'          => $validated['misi']          ?? $identitas->misi,
                    'jml_fakultas'  => $validated['jml_fakultas']  ?? $identitas->jml_fakultas,
                    'jml_prodi'     => $validated['jml_prodi']     ?? $identitas->jml_prodi,
                    'jml_dosen'     => $validated['jml_dosen']     ?? $identitas->jml_dosen,
                    'jml_tendik'    => $validated['jml_tendik']    ?? $identitas->jml_tendik,
                    'jml_mahasiswa' => $validated['jml_mahasiswa'] ?? $identitas->jml_mahasiswa,
                    'jml_ormawa'    => $validated['jml_ormawa']    ?? $identitas->jml_ormawa,
                    'jml_ukm'       => $validated['jml_ukm']       ?? $identitas->jml_ukm,
                ]);

                // 3. Update demografi agama (upsert per agama)
                if (!empty($validated['agamas']) && is_array($validated['agamas'])) {
                    foreach ($validated['agamas'] as $agamaKey => $jumlah) {
                        $this->assessmentRepository->upsertAgama($identitas->id, $agamaKey, (int) ($jumlah ?: 0));
                    }
                }
            }

            $freshAssessment = $this->assessmentRepository->find($assessment->id);
            $freshUser = $this->userRepository->find($userId);

            return $this->successResponse([
                'nama_pic'    => $freshAssessment->nama_pic,
                'jabatan_pic' => $freshAssessment->jabatan_pic,
                'no_hp_pic'   => $freshAssessment->no_hp_pic,
                'email'       => $freshUser->email,
            ], 'Profil berhasil diperbarui.', 200);
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
            return $this->errorResponse($th->getMessage(), $code);
        }
    }

    /**
     * Helper internal untuk mem-build DTO dengan konteks User Auth yang aman.
     */
    public function getAuthDTO(): AssessmentDTO
    {
        $userId = $this->getAuth();
        return new AssessmentDTO($userId);
    }
}

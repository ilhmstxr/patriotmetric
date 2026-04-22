<?php

namespace App\Services;

use App\DTO\AuthDTO\LoginDTO;
use App\DTO\AuthDTO\registerDTO;
use App\Models\Institusi;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


/**
 * @property \App\Repositories\UserrRepository $repository
 */
class UserService extends BaseService
{
    /**
     * UserService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }
    public function register(registerDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            // 1. Buat User (PIC) melalui Repository
            $user = $this->repository->createUser([
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'role' => 'SUBMITTER',
                'status' => 'PENDING',
            ]);

            // 2. Buat Institusi terkait (Melewati Repository yang benar)
            // Kita kirimkan user_id yang baru saja dibuat
            $this->repository->createInstitusi([
                'name' => $dto->namaPt,
                'category' => $dto->kategoriPt,
                'user_id' => $user->id,
            ]);

            return $user;
        });
    }

    /**
     * LOGIN: Pengecekan Status & Kredensial
     */
    public function login(LoginDTO $dto)
    {
        // 1. Cek User berdasarkan email
        $user = $this->repository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new Exception("Email atau password salah.", 401);
        }

        // 2. Cek Status Akun (Keamanan Utama)
        if ($user->status === 'BANNED' || $user->status === 'SUSPENDED') {
            throw new Exception("Akun Anda telah ditangguhkan. Silakan hubungi admin.", 403);
        }

        // 3. Invalidate Session Lama (Single Session Policy)
        // Jika menggunakan Sanctum:
        $user->tokens()->delete();

        // 4. Generate Token / Login Session
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * LOGOUT: Pembersihan Token & Sesi
     */
    public function logout(User $user)
    {
        // Hapus token saat ini
        return $user->currentAccessToken()->delete();
    }
}

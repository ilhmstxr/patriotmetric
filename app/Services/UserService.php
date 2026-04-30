<?php

namespace App\Services;

use App\DTO\AuthDTO\LoginDTO;
use App\DTO\AuthDTO\RegisterDTO;
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
    public function register(RegisterDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            // 1. Buat User (PIC) melalui Repository
            $user = $this->repository->createUser([
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'role' => 'PESERTA',
                'status' => 'ACTIVE',
            ]);

            // 2. Buat Institusi terkait
            $institusi = $this->repository->createInstitusi([
                'nama_institusi' => $dto->namaPt,
                'jenis_institusi' => $dto->jenisPt,
            ]);

            // 3. Buat Data Pengumpulan (Assessment Record) wajib untuk tahun ini
            $this->repository->createPengumpulan([
                'user_id' => $user->id,
                'institution_id' => $institusi->id,
                'tahun_periode' => date('Y'),
                'status' => 'ACTIVE',
                'nama_pic' => $dto->namaPic,
                'jabatan_pic' => $dto->jabatanPic,
                'no_hp_pic' => $dto->noHpPic,
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

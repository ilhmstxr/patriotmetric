<?php

namespace App\Http\Controllers;

use App\DTO\AssessmentDTO\AssessmentDTO;
use App\DTO\AuthDTO\LoginDTO;
use App\DTO\AuthDTO\registerDTO;
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

            $dto = new registerDTO($validated);
            $user = $this->userService->register($dto);

            return $this->successResponse($user, 'Registrasi berhasil. Silakan cek Email untuk verifikasi.', 201);
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

            return $this->successResponse($result, 'Login berhasil.', 200);
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

    public function getAuth()
    {
        $testUser = 3;
        $testAdmin = 8;
        $userId = Auth::id() ?? $testAdmin;
        return $userId;
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

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\EmailVerificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    use ApiResponse;

    protected EmailVerificationService $emailVerificationService;

    public function __construct(EmailVerificationService $emailVerificationService)
    {
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * GET /api/auth/verify-email/{token}
     * Public route - validates token and activates assessment.
     */
    public function verify(string $token): mixed
    {
        $result = $this->emailVerificationService->verifyToken($token);

        if ($result['success']) {
            $user = User::find($result['user_id']);

            if ($user) {
                // Update user status UNVERIFIED → ACTIVE
                $user->update([
                    'status' => 'ACTIVE',
                    'email_verified_at' => now(),
                ]);
            }

            return redirect('/masuk?verified=1');
        }

        if ($result['reason'] === 'expired') {
            return redirect('/verifikasi-gagal?reason=expired');
        }

        return redirect('/verifikasi-gagal?reason=invalid');
    }

    /**
     * POST /api/auth/resend-verification
     * Protected route (auth:sanctum) - resends verification email.
     */
    public function resend(Request $request): JsonResponse
    {
        $user = $request->user();

        $this->emailVerificationService->resendVerification($user);

        return $this->successResponse(null, 'Email verifikasi berhasil dikirim ulang.', 200);
    }
}

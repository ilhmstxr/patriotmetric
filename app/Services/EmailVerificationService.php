<?php

namespace App\Services;

use App\Mail\EmailVerificationMail;
use App\Models\EmailVerificationToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailVerificationService
{
    /**
     * Generate a verification token and send the verification email.
     */
    public function generateAndSendVerification(User $user, string $institutionName): void
    {
        $token = Str::random(64);

        EmailVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(60),
        ]);

        $verificationUrl = config('app.url') . '/api/auth/verify-email/' . $token;

        Mail::to($user->email)->queue(
            new EmailVerificationMail($user, $verificationUrl, $institutionName)
        );
    }

    /**
     * Verify a token and return the result.
     *
     * @return array{success: bool, user_id: ?int, reason: ?string}
     */
    public function verifyToken(string $token): array
    {
        return DB::transaction(function () use ($token) {
            $verificationToken = EmailVerificationToken::where('token', $token)
                ->lockForUpdate()
                ->first();

            if (!$verificationToken) {
                return [
                    'success' => false,
                    'user_id' => null,
                    'reason' => 'invalid',
                ];
            }

            if ($verificationToken->isUsed()) {
                return [
                    'success' => false,
                    'user_id' => null,
                    'reason' => 'invalid',
                ];
            }

            if ($verificationToken->isExpired()) {
                return [
                    'success' => false,
                    'user_id' => $verificationToken->user_id,
                    'reason' => 'expired',
                ];
            }

            // Token is valid — mark as used
            $verificationToken->update(['used_at' => now()]);

            return [
                'success' => true,
                'user_id' => $verificationToken->user_id,
                'reason' => null,
            ];
        });
    }

    /**
     * Resend verification email to the user.
     * Invalidates existing tokens first, then generates a new one.
     */
    public function resendVerification(User $user): void
    {
        $this->invalidateExistingTokens($user->id);

        // Get institution name from user's assessment → institusi relation
        $institutionName = $user->assessments()
            ->with('institusi')
            ->latest()
            ->first()
            ?->institusi
            ?->nama_institusi ?? 'Institusi';

        $this->generateAndSendVerification($user, $institutionName);
    }

    /**
     * Invalidate all existing unused tokens for a user.
     */
    public function invalidateExistingTokens(int $userId): void
    {
        EmailVerificationToken::where('user_id', $userId)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);
    }
}

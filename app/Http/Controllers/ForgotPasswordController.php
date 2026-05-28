<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    use ApiResponse;

    /**
     * Send a password reset link to the given email.
     *
     * POST /api/auth/forgot-password
     */
    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->email;

            // Check email exists in users table
            $user = User::where('email', $email)->first();
            if (!$user) {
                return $this->errorResponse('Email tidak terdaftar dalam sistem', 422);
            }

            // Throttle: check if a token was created less than 60 seconds ago
            $existingToken = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->first();

            if ($existingToken && Carbon::parse($existingToken->created_at)->addSeconds(60)->isFuture()) {
                return $this->errorResponse('Silakan tunggu 60 detik sebelum meminta ulang', 429);
            }

            // Generate unique token
            $plainToken = Str::random(64);
            $hashedToken = Hash::make($plainToken);

            // Store hashed token in password_reset_tokens table (upsert)
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => $hashedToken,
                    'created_at' => Carbon::now(),
                ]
            );

            // Build reset URL
            $resetUrl = config('app.url') . '/reset-password/' . $plainToken . '?email=' . urlencode($email);

            // Send reset email
            Mail::to($email)->send(new ResetPasswordMail($user, $resetUrl));

            return $this->successResponse(null, 'Link reset password telah dikirim ke email Anda.', 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 422, $e->errors());
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
            return $this->errorResponse('Gagal mengirim email. Silakan coba lagi nanti.', $code);
        }
    }

    /**
     * Reset the user's password using the token.
     *
     * POST /api/auth/reset-password
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string|min:8|max:128|confirmed',
            ], [
                'password.min' => 'Password minimal 8 karakter',
                'password.max' => 'Password maksimal 128 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ]);

            $email = $request->email;
            $token = $request->token;

            // Lookup password_reset_tokens by email
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->first();

            if (!$resetRecord) {
                return $this->errorResponse('Link tidak valid atau sudah digunakan', 422);
            }

            // Verify token not expired (created_at + 60 minutes > now)
            if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
                return $this->errorResponse('Link reset sudah kedaluwarsa. Silakan minta link baru.', 422);
            }

            // Verify token matches
            if (!Hash::check($token, $resetRecord->token)) {
                return $this->errorResponse('Link tidak valid atau sudah digunakan', 422);
            }

            // Find user and update password
            $user = User::where('email', $email)->first();
            if (!$user) {
                return $this->errorResponse('Email tidak terdaftar dalam sistem', 422);
            }

            $user->update(['password' => Hash::make($request->password)]);

            // Invalidate all Sanctum tokens
            $user->tokens()->delete();

            // Delete used token from password_reset_tokens
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return $this->successResponse([
                'redirect_to' => '/masuk',
            ], 'Password berhasil direset. Silakan login dengan password baru Anda.', 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->getMessage(), 422, $e->errors());
        } catch (\Throwable $th) {
            $code = (is_numeric($th->getCode()) && $th->getCode() >= 400 && $th->getCode() < 600) ? $th->getCode() : 500;
            return $this->errorResponse($th->getMessage(), $code);
        }
    }
}

<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (! Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.email' => 'Login gagal: email atau password salah.',
            ]);
        }

        $user = Auth::user();

        $isAdmin = strtoupper($user->role) === 'ADMIN';
        $isActive = strtoupper($user->status) === 'ACTIVE';

        if (! $isAdmin || ! $isActive) {
            Auth::logout();

            throw ValidationException::withMessages([
                'data.email' => "DEBUG: role={$user->role}, status={$user->status}, is_admin=" . ($isAdmin ? 'true' : 'false') . ", is_active=" . ($isActive ? 'true' : 'false'),
            ]);
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}

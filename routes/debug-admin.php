<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Temporary debug route - DELETE after debugging
Route::post('/debug-admin-login', function () {
    $credentials = request()->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'success' => false,
            'message' => 'Login gagal: email atau password salah.',
            'email_used' => $credentials['email'],
        ]);
    }

    $user = Auth::user();
    $isAdmin = strtoupper($user->role) === 'ADMIN';
    $isActive = strtoupper($user->status) === 'ACTIVE';

    Auth::logout();

    return response()->json([
        'success' => true,
        'user_id' => $user->id,
        'email' => $user->email,
        'role' => $user->role,
        'role_check' => strtoupper($user->role),
        'status' => $user->status,
        'status_check' => strtoupper($user->status),
        'is_admin' => $isAdmin,
        'is_active' => $isActive,
        'would_pass_canAccessPanel' => $isAdmin && $isActive,
    ]);
});

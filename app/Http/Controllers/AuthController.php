<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Registrasi awal (Nama PT, PIC, dll).
        return redirect()->back()->with('message', 'Registrasi berhasil');
    }

    public function login(Request $request)
    {
        // Login & hapus sesi di perangkat lain (Invalidate old session).
        return redirect()->back()->with('message', 'Login berhasil');
    }

    public function logout(Request $request)
    {
        // Logout & hapus last_session_id.
        return redirect()->route('login');
    }
}

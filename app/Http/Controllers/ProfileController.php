<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        // Mengambil data profil dan status (verifying, peserta, dll).
        return Inertia::render('Profile/Index', [
            'profile' => []
        ]);
    }

    public function baseline(Request $request)
    {
        // Mengisi data Jumlah Mhs, Dosen, dll (Hanya bisa sekali/sebelum dikunci).
        return redirect()->back()->with('message', 'Baseline berhasil disimpan');
    }

    public function status()
    {
        // Mengecek status verifikasi dari Admin Pusat.
        return Inertia::render('Profile/Status', [
            'status' => 'verifying'
        ]);
    }
}

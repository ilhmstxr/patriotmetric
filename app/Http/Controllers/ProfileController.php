<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\ApiResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function index()
    {
        // Mengambil data profil dan status (verifying, peserta, dll).
         // return Inertia::render('Profile/Index', [
        //     'profile' => []
        // ]);
        $data = [
            'profile' => []
        ];
        return $this->successResponse($data, 'Data profil berhasil diambil', 200);
    }

    public function baseline(Request $request)
    {
        // Mengisi data Jumlah Mhs, Dosen, dll (Hanya bisa sekali/sebelum dikunci).
        $newData = [
            'baseline' => $request->all()
        ];
        return $this->successResponse($newData, 'Baseline berhasil disimpan', 201);
    }

    public function status()
    {
        // Mengecek status verifikasi dari Admin Pusat.
           // return Inertia::render('Profile/Status', [
        //     'status' => 'verifying'
        // ]);
        $data = [
            'status' => 'verifying'
        ];
        return $this->successResponse($data, 'Status verifikasi berhasil diambil', 200);
    }
}

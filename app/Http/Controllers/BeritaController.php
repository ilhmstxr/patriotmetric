<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::published()
            ->orderByDesc('tanggal')
            ->get();

        return view('berita', compact('beritas'));
    }

    public function show(string $slug)
    {
        $berita = Berita::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('berita-detail', compact('berita'));
    }
}

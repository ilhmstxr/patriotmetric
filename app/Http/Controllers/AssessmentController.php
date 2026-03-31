<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function questions()
    {
        // Mengambil daftar soal (A, B, C) beserta rumus/konstanta.
        return Inertia::render('Assessment/Questions', [
            'questions' => []
        ]);
    }

    public function answers(Request $request)
    {
        // Menyimpan klaim jawaban + URL Link Drive (Wajib).
        return redirect()->back()->with('message', 'Jawaban berhasil disimpan');
    }

    public function submit(Request $request)
    {
        // Final Submit: Mengubah status menjadi submitted & kunci editing.
        return redirect()->back()->with('message', 'Assesment berhasil disubmit');
    }

    public function preview()
    {
        // Mengambil Ranged Score (Skala 1-5) untuk dashboard.
        return Inertia::render('Assessment/Preview', [
            'score' => 0
        ]);
    }
}

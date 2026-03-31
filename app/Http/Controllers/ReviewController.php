<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function submissions()
    {
        // List institusi yang sudah melakukan Final Submit.
        return Inertia::render('Review/Submissions', [
            'submissions' => []
        ]);
    }

    public function submissionDetail($id)
    {
        // Detail jawaban institusi tertentu (Klaim + Link Drive).
        return Inertia::render('Review/SubmissionDetail', [
            'submission' => []
        ]);
    }

    public function updateAnswer(Request $request, $id)
    {
        // Verdict: Reviewer input angka granular. Memicu kalkulasi skor.
        return redirect()->back()->with('message', 'Skor berhasil diupdate');
    }

    public function publish(Request $request, $id)
    {
        // Admin Pusat mempublikasikan hasil agar skor asli muncul di user.
        return redirect()->back()->with('message', 'Hasil berhasil dipublikasikan');
    }
}

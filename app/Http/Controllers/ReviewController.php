<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\ApiResponse;

class ReviewController extends Controller
{
    use ApiResponse;

    protected $reviewService;

    public function __construct(\App\Services\ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function getSteps(Request $request, $id)
    {
        $dto = new \App\DTOs\ReviewDTO();
        $dto->submissionId = $id;

        $progress = $this->reviewService->getStepperProgress($dto);

        return $this->successResponse($progress, 'Data stepper progress berhasil diambil', 200);
    }

    public function index()
    {
        // List institusi yang sudah melakukan Final Submit.
            // return Inertia::render('Review/Submissions', [
        //     'submissions' => []
        // ]);
        $data = [
            'submissions' => []
        ];
        return $this->successResponse($data, 'Daftar submission berhasil diambil', 200);
    }

    public function submissionDetail($id)
    {
        // Detail jawaban institusi tertentu (Klaim + Link Drive).
            // return Inertia::render('Review/SubmissionDetail', [
        //     'submission' => []
        // ]);
        $data = [
            'submission' => []
        ];
        return $this->successResponse($data, 'Detail submission berhasil diambil', 200);
    }

    public function updateAnswer(Request $request, $id)
    {
        // Verdict: Reviewer input angka granular. Memicu kalkulasi skor.
        $patchedData = [
            'id' => $id,
            'verdict' => $request->verdict ?? null
        ];
        return $this->successResponse($patchedData, 'Skor berhasil diupdate', 200);
    }

    public function publish(Request $request, $id)
    {
        // Admin Pusat mempublikasikan hasil agar skor asli muncul di user.
        $publishedData = [
            'id' => $id,
            'status' => 'published'
        ];
        return $this->successResponse($publishedData, 'Hasil berhasil dipublikasikan', 200);
    }
}

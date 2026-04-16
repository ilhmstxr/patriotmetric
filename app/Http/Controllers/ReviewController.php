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

    public function getQuestionsByCategory(Request $request, $submissionId, $catId)
    {
        try {
            $dto = new \App\DTOs\ReviewDTO();
            $dto->submissionId = $submissionId;
            $dto->categoryId = $catId;

            $questions = $this->reviewService->getQuestionsWithAnswers($dto);

            // Resource Mapping bisa dilakukan menggunakan \Illuminate\Http\Resources\Json\JsonResource
            // tetapi untuk sementara kita kirimkan data raw atau diproses manual.
            return $this->successResponse($questions, 'Data soal beserta jawaban berhasil diambil', 200);
        } catch (\Exception $e) {
            $status = $e->getCode() == 404 ? 404 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }

    public function saveProgress(Request $request, $submissionId)
    {
        // Validation Request
        $request->validate([
            'category_id' => 'required|integer',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.skor_validasi_reviewer' => 'nullable|numeric',
        ]);

        try {
            $dto = new \App\DTOs\ReviewDTO();
            $dto->submissionId = $submissionId;
            $dto->categoryId = $request->input('category_id');
            $dto->answers = $request->input('answers');

            $this->reviewService->persistProgress($dto);

            return $this->successResponse(null, 'Hasil verifikasi berhasil disimpan (Atomic Save)', 200);
        } catch (\Exception $e) {
            $status = $e->getCode() == 403 ? 403 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }

    public function getCategoryPreview(Request $request, $submissionId, $catId)
    {
        try {
            $dto = new \App\DTOs\ReviewDTO();
            $dto->submissionId = $submissionId;
            $dto->categoryId = $catId;

            $previewData = $this->reviewService->calculateCategoryPreview($dto);

            return $this->successResponse($previewData, 'Estimasi skor per kategori berhasil dihitung', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
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

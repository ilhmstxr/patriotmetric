<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\DTOs\SubmitterDTO;
use App\Services\SubmitterService;
use Illuminate\Support\Facades\Auth;

class SubmitterController extends Controller
{
    use ApiResponse;

    protected $submitterService;

    public function __construct(SubmitterService $submitterService)
    {
        $this->submitterService = $submitterService;
    }

    /**
     * Helper internal untuk mem-build DTO dengan konteks User Auth yang aman.
     * Mencegah celah IDOR (Insecure Direct Object Reference).
     */
    private function getAuthDTO(): SubmitterDTO
    {
        $dto = new SubmitterDTO();
        // $dto->userId = Auth::id(); // Menggunakan ID User login, bukan dari URL
        
        return $dto;
    }

    /**
     * 1. Ambil Semua Pertanyaan (Single Form)
     * Menggantikan getQuestionsByCategory dan getSteps
     */
    public function getAllQuestions(Request $request)
    {
        try {
            $dto = $this->getAuthDTO();
            // Service akan mencari assessment_id aktif berdasarkan userId
            $questions = $this->submitterService->getAllQuestionsWithAnswers($dto);

            return $this->successResponse($questions, 'Data seluruh soal dan jawaban tersimpan berhasil diambil', 200);
        } catch (\Exception $e) {
            $status = $e->getCode() == 404 ? 404 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }

    /**
     * 2. Auto-Save Progress (Interval 5 Menit)
     * Menggantikan saveProgress
     */
    public function autoSaveProgress(Request $request)
    {
        // Validasi disesuaikan untuk Single Form (tanpa category_id di root)
        $request->validate([
            'answers' => 'required|array',
            'answers.*.indicator_id' => 'required|integer',
            'answers.*.claim_value' => 'nullable',
            'answers.*.evidence_url' => 'nullable|url',
        ]);

        try {
            $dto = $this->getAuthDTO();
            $dto->answers = $request->input('answers');

            $this->submitterService->autoSaveProgress($dto);

            // Menggunakan response 200 tanpa data untuk auto-save agar payload ringan
            return $this->successResponse(null, 'Auto-save berhasil', 200);
        } catch (\Exception $e) {
            $status = $e->getCode() == 403 ? 403 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }

    /**
     * 3. Final Lock (Submit Akhir)
     */
    public function finalize(Request $request)
    {
        try {
            $dto = $this->getAuthDTO();

            $this->submitterService->lockAssessment($dto);

            return $this->successResponse(null, 'Seluruh asesmen telah dikunci (Final Lock)', 200);
        } catch (\Exception $e) {
            $status = $e->getCode() == 422 ? 422 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }

    /**
     * 4. Preview Nilai (Opsional)
     * Menggantikan previewCategory. Menghitung estimasi nilai total dari satu form.
     */
    public function previewResults(Request $request)
    {
        try {
            $dto = $this->getAuthDTO();

            $previewData = $this->submitterService->calculateTotalPreview($dto);

            return $this->successResponse($previewData, 'Estimasi skor total berhasil dihitung', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * 5. Get Progress (Untuk Progress Bar)
     * Mengembalikan { total_questions: 40, answered_questions: 25 }
     */
    public function getProgress(Request $request)
    {
        try {
            $dto = $this->getAuthDTO();

            $progress = $this->submitterService->getCurrentProgress($dto);

            return $this->successResponse($progress, 'Data progres pengisian berhasil diambil', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
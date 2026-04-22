<?php

namespace App\Http\Controllers;

use App\DTO\SubmitterDTO\BaselineDTO;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\DTO\SubmitterDTO\SubmitterDTO;
use App\DTO\SubmitterDTO\QuestionDTO;
use App\Http\Requests\BaselineSubmitterRequest;
use App\Services\SubmitterService;
use Illuminate\Support\Facades\Auth;
use Exception;

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
    private function getValidatedAssessment(string $mode)
    {
        $userId = Auth::id() ?? 3;
        $authDto = new SubmitterDTO($userId);

        // Kita panggil fungsi validate di service yang bertindak sebagai dispatcher
        return $this->submitterService->validate($authDto, $mode);
    }

    private function getErrorCode(\Throwable $e)
    {
        $code = $e->getCode();
        // Pastikan code adalah HTTP status code yang valid
        return (is_numeric($code) && $code >= 400 && $code < 600) ? $code : 500;
    }

    public function storeBaseline(BaselineSubmitterRequest $request, $userId)
    // DONE
    {
        try {
            $validatedData = $request->validated();

            $dto = new BaselineDTO((int) $userId, $validatedData);

            $this->submitterService->storeBaseline($dto);

            return $this->successResponse(null, 'Data baseline berhasil disimpan', 200);
        } catch (\Throwable $e) {
            $status = $e->getCode() == 403 ? 403 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }

    /**
     * 1. Ambil Semua Pertanyaan (Single Form)
     * Menggantikan getQuestionsByCategory dan getSteps
     */
    public function getAllQuestions()
    {
        try {
            // Gunakan mode ANY agar status SUBMITTED tetap bisa melihat soal
            $assessment = $this->getValidatedAssessment(SubmitterService::MODE_ANY);

            $questions = $this->submitterService->getAllPertanyaan();

            return $this->successResponse($questions, 'Data berhasil diambil', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
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

    public function finalize(Request $request)
    {
        try {
            // PROTEKSI: Paksa harus mode WRITE
            $assessment = $this->getValidatedAssessment(SubmitterService::MODE_WRITE);

            $this->submitterService->lockAssessment($assessment);

            return $this->successResponse(null, 'Seluruh asesmen telah dikunci (Final Lock)', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }


    public function previewResults(Request $request)
    {
        try {
            // PROTEKSI: Paksa harus mode READ (Sudah Final)
            $assessment = $this->getValidatedAssessment(SubmitterService::MODE_READ);

            $previewData = $this->submitterService->calculateTotalPreview($assessment);

            return $this->successResponse($previewData, 'Estimasi skor total berhasil dihitung', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
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

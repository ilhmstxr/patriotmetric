<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class SubmitterController extends Controller
{
    use ApiResponse;

    protected $submitterService;

    public function __construct(\App\Services\SubmitterService $submitterService)
    {
        $this->submitterService = $submitterService;
    }

    public function getSteps(Request $request, $assessmentId)
    {
        $dto = new \App\DTOs\SubmitterDTO();
        $dto->assessmentId = $assessmentId;

        $progress = $this->submitterService->getStepperProgress($dto);

        return $this->successResponse($progress, 'Data stepper progress berhasil diambil', 200);
    }

    public function getQuestionsByCategory(Request $request, $assessmentId, $catId)
    {
        try {
            $dto = new \App\DTOs\SubmitterDTO();
            $dto->assessmentId = $assessmentId;
            $dto->categoryId = $catId;

            $questions = $this->submitterService->getQuestionsWithAnswers($dto);

            return $this->successResponse($questions, 'Data soal beserta jawaban berhasil diambil', 200);
        } catch (\Exception $e) {
            $status = $e->getCode() == 404 ? 404 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }

    public function saveProgress(Request $request, $assessmentId)
    {
        $request->validate([
            'category_id' => 'required|integer',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required_without:answers.*.indicator_id|integer',
            'answers.*.claim_value' => 'nullable',
            'answers.*.evidence_url' => 'nullable|url',
        ]);

        try {
            $dto = new \App\DTOs\SubmitterDTO();
            $dto->assessmentId = $assessmentId;
            $dto->categoryId = $request->input('category_id');
            $dto->answers = $request->input('answers');

            $this->submitterService->persistProgress($dto);

            return $this->successResponse(null, 'Progres berhasil disimpan ke kategori', 200);
        } catch (\Exception $e) {
            $status = $e->getCode() == 403 ? 403 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }

    public function getCategoryPreview(Request $request, $assessmentId, $catId)
    {
        try {
            $dto = new \App\DTOs\SubmitterDTO();
            $dto->assessmentId = $assessmentId;
            $dto->categoryId = $catId;

            $previewData = $this->submitterService->calculateCategoryPreview($dto);

            return $this->successResponse($previewData, 'Estimasi skor per kategori berhasil dihitung', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function finalize(Request $request, $assessmentId)
    {
        try {
            $dto = new \App\DTOs\SubmitterDTO();
            $dto->assessmentId = $assessmentId;

            $this->submitterService->lockAssessment($dto);

            return $this->successResponse(null, 'Seluruh asesmen telah dikunci (Final Lock)', 200);
        } catch (\Exception $e) {
            $status = $e->getCode() == 422 ? 422 : 500;
            return $this->errorResponse($e->getMessage(), $status);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\DTO\AssessmentDTO\BaselineDTO;
use App\DTO\AssessmentDTO\JawabanDTO;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\DTO\AssessmentDTO\AssessmentDTO;
use App\DTO\AssessmentDTO\QuestionDTO;
use App\Http\Requests\BaselinePesertaRequest;
use App\Services\AssessmentService;
use Exception;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    use ApiResponse;

    protected $AssessmentService;

    public function __construct(AssessmentService $AssessmentService)
    {
        $this->AssessmentService = $AssessmentService;
    }


    private function getValidatedAssessment(string $mode)
    {
        $testUser = 3;
        $userId = Auth::id() ?? $testUser;
        
        $authDto = new AssessmentDTO((int) $userId);

        // Kita panggil fungsi validate di service yang bertindak sebagai dispatcher
        return $this->AssessmentService->validate($authDto, $mode);
    }

    private function getErrorCode(\Throwable $e)
    {
        $code = $e->getCode();
        // Pastikan code adalah HTTP status code yang valid
        return (is_numeric($code) && $code >= 400 && $code < 600) ? $code : 500;
    }

    public function storeBaseline(BaselinePesertaRequest $request)
    // DONE
    {
        // profil
        try {
            $validatedData = $request->validated();
            $assessment = $this->getValidatedAssessment(AssessmentService::MODE_WRITE);

            $dto = new BaselineDTO((int) $assessment->user_id, $validatedData);

            // return $dto;
            // 2. Eksekusi Service (Orchestration)
            $result = $this->AssessmentService->upsertBaseline($dto);

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
    public function getAllQuestions($assessmentId = null)
    {
        try {
            // Gunakan mode ANY agar status SUBMITTED tetap bisa melihat soal
            $assessment = $this->getValidatedAssessment(AssessmentService::MODE_ANY);

            $questions = $this->AssessmentService->getAllPertanyaan();

            return $this->successResponse($questions, 'Data berhasil diambil', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }

    public function getProfilePeserta($pesertaId)
    {
        try {
            $assessment = $this->getValidatedAssessment(AssessmentService::MODE_ANY);

            $profile = $this->AssessmentService->getProfilePeserta($pesertaId);

            return $this->successResponse($profile, 'Data berhasil diambil', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $this->getErrorCode($th));
        }
    }

    public function saveJawaban(Request $request)
    {
        try {
            // 1. Otorisasi & Validasi Status via Helper (MODE_WRITE)
            // Jika status SUBMITTED/GRADED, akan otomatis lempar Exception 403
            $assessment = $this->getValidatedAssessment(AssessmentService::MODE_WRITE);

            $validated = $request->validate([
                'pertanyaan_id' => 'required|integer',
                'jawaban_id'    => 'nullable|integer',
                'jawaban_teks'  => 'nullable|string',
                'tautan_bukti'  => 'nullable|url',
                'note_reviewer' => 'nullable|string',
            ]);
            // return $validated;

            $dto = new JawabanDTO($assessment->id, $validated);

            // 3. Eksekusi Service
            $result = $this->AssessmentService->storeJawaban($dto);

            // return $result;
            return $this->successResponse($result, 'Jawaban berhasil disimpan.', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }

    public function finalize(Request $request)
    {
        try {
            // PROTEKSI: Paksa harus mode WRITE
            $assessment = $this->getValidatedAssessment(AssessmentService::MODE_WRITE);

            $this->AssessmentService->lockAssessment($assessment);

            return $this->successResponse(null, 'Seluruh asesmen telah dikunci (Final Lock)', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }


    public function previewResults(Request $request)
    {
        try {
            // PROTEKSI: Paksa harus mode READ (Sudah Final)
            $assessment = $this->getValidatedAssessment(AssessmentService::MODE_READ);

            $previewData = $this->AssessmentService->calculateTotalPreview($assessment);

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

            $progress = $this->AssessmentService->getCurrentProgress($dto);

            return $this->successResponse($progress, 'Data progres pengisian berhasil diambil', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\AssessmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewerController extends Controller
{
    use ApiResponse;

    protected $assessmentService;

    public function __construct(AssessmentService $assessmentService)
    {
        $this->assessmentService = $assessmentService;
    }

    private function getErrorCode(\Throwable $e)
    {
        $code = $e->getCode();
        return (is_numeric($code) && $code >= 400 && $code < 600) ? $code : 500;
    }

    /**
     * Endpoint: GET /api/reviewer/tasks
     * Mengambil daftar tugas (plottingan) institusi yang harus dinilai.
     */
    public function getAssignedTasks(Request $request)
    {
        try {
            // Ambil ID Auth Reviewer yang sedang login (Fallback 4 untuk testing Bruno)
            $reviewerId = Auth::id() ?? 8; 
            // return $reviewerId;

            // Eksekusi Service
            $result = $this->assessmentService->getAssignedReviews($reviewerId);

            return $this->successResponse($result, 'Daftar plottingan tugas berhasil diambil.', 200);
            
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $this->getErrorCode($e));
        }
    }
}
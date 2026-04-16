<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

// service
use App\Services\ReviewService;
use App\Services\RubrikService;
use App\Services\SubmissionService;
use App\Traits\ApiResponse;

// DTO

class AssessmentController extends Controller
{
    use ApiResponse;

    protected $reviewService;
    protected $rubrikService;
    protected $submissionService;

    public function __construct(
        ReviewService $reviewService,
        RubrikService $rubrikService,
        SubmissionService $submissionService
    ) {
        $this->reviewService = $reviewService;
        $this->rubrikService = $rubrikService;
        $this->submissionService = $submissionService;
    }

    public function questions()
    {
        try {
            $questions = $this->rubrikService->getRubrikStructure();
            // return Inertia::render('Assessment/Questions', [
            //     'questions' => $questions
            // ]);
            return $this->successResponse($questions, 'Struktur rubrik berhasil diambil', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function answers(Request $request)
    {
        try {
            $this->submissionService->saveDraft($request->answers ?? []);
            return $this->successResponse(null, 'Jawaban berhasil disimpan', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function submit(Request $request)
    {
        try {
            $this->submissionService->lockSubmission($request->submission_id);
            return $this->successResponse(null, 'Assesment berhasil disubmit', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function preview(Request $request)
    {
        try {
            $score = $this->submissionService->getPreviewScore($request->submission_id);
            // return Inertia::render('Assessment/Preview', [
            //     'score' => $score
            // ]);
            return $this->successResponse($score, 'Skor final berhasil diambil', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }


    // rubrik
    public function getRubrikStructure(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->rubrikService->getRubrikStructure();
            // RESPONSE
            return $this->successResponse(null, 'Struktur rubrik berhasil diambil', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function getCategoryMetadata(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->rubrikService->getCategoryMetadata();
            // RESPONSE
            return $this->successResponse(null, 'Metadata kategori berhasil diambil', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function validateRubrikConsistency(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->rubrikService->validateRubrikConsistency();
            // RESPONSE
            return $this->successResponse(null, 'Konsistensi rubrik valid (100%)', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }


    // submitter

    public function getPreviewScore(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->submissionService->getPreviewScore($request->submission_id);
            // RESPONSE
            return $this->successResponse(null, 'Skor final berhasil diambil', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
    public function getTaskDetails(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->submissionService->getTaskDetails($request->submission_id);
            // RESPONSE
            return $this->successResponse(null, 'Detail tugas berhasil diambil', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function saveDraft(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->submissionService->saveDraft($request->answers ?? []);
            // RESPONSE
            return $this->successResponse(null, 'Draft berhasil disimpan', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    // reviewer
    public function verifySingleIndicator(Request $request)
    {
        try {
            $this->reviewService->verifySingleIndicator(
                $request->submission_id,
                $request->indicator_id,
                $request->verified_score,
                $request->notes ?? null
            );
            return $this->successResponse(null, 'Indikator berhasil diverifikasi', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
    public function getFinalScore(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->getFinalScore($request->submission_id);
            // RESPONSE
            return $this->successResponse(null, 'Skor final berhasil diambil', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
    public function assignReviewersToSubmissions(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->assignReviewersToSubmissions($request->reviewer_id, $request->submission_ids);
            // RESPONSE
            return $this->successResponse(null, 'Reviewer berhasil ditugaskan', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function getAssignedSubmissions(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->getAssignedSubmissions($request->reviewer_id);
            // RESPONSE
            return $this->successResponse(null, 'Daftar penugasan berhasil diambil', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function calculateVerifiedFinalScore(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->calculateVerifiedFinalScore($request->verified_answers ?? [], $request->metadata ?? []);
            // RESPONSE
            return $this->successResponse(null, 'Skor verifikasi berhasil dihitung', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function finalizeReview(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->finalizeReview($request->submission_id);
            // RESPONSE
            return $this->successResponse(null, 'Review berhasil difinalisasi', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}

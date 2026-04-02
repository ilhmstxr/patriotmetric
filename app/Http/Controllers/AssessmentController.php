<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

// service
use App\Services\ReviewService;
use App\Services\RubrikService;
use App\Services\SubmissionService;

// DTO

class AssessmentController extends Controller
{
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
            return Inertia::render('Assessment/Questions', [
                'questions' => $questions
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil daftar soal');
        }
    }

    public function answers(Request $request)
    {
        try {
            $this->submissionService->saveDraft($request->answers ?? []);
            return redirect()->back()->with('message', 'Jawaban berhasil disimpan');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan jawaban');
        }
    }

    public function submit(Request $request)
    {
        try {
            $this->submissionService->lockSubmission($request->submission_id);
            return redirect()->back()->with('message', 'Assesment berhasil disubmit');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function preview(Request $request)
    {
        try {
            $score = $this->submissionService->getPreviewScore($request->submission_id);
            return Inertia::render('Assessment/Preview', [
                'score' => $score
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil preview skor');
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
            return redirect()->back()->with('message', 'Struktur rubrik berhasil diambil');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil struktur rubrik');
        }
    }

    public function getCategoryMetadata(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->rubrikService->getCategoryMetadata();
            // RESPONSE
            return redirect()->back()->with('message', 'Metadata kategori berhasil diambil');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil metadata kategori');
        }
    }

    public function validateRubrikConsistency(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->rubrikService->validateRubrikConsistency();
            // RESPONSE
            return redirect()->back()->with('message', 'Konsistensi rubrik valid (100%)');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
            return redirect()->back()->with('message', 'Skor final berhasil diambil');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil skor final');
        }
    }
    public function getTaskDetails(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->submissionService->getTaskDetails($request->submission_id);
            // RESPONSE
            return redirect()->back()->with('message', 'Detail tugas berhasil diambil');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil detail tugas');
        }
    }

    public function saveDraft(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->submissionService->saveDraft($request->answers ?? []);
            // RESPONSE
            return redirect()->back()->with('message', 'Draft berhasil disimpan');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan draft');
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
            return redirect()->back()->with('message', 'Indikator berhasil diverifikasi');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function getFinalScore(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->getFinalScore($request->submission_id);
            // RESPONSE
            return redirect()->back()->with('message', 'Skor final berhasil diambil');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil skor final');
        }
    }
    public function assignReviewersToSubmissions(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->assignReviewersToSubmissions($request->reviewer_id, $request->submission_ids);
            // RESPONSE
            return redirect()->back()->with('message', 'Reviewer berhasil ditugaskan');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menugaskan reviewer');
        }
    }

    public function getAssignedSubmissions(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->getAssignedSubmissions($request->reviewer_id);
            // RESPONSE
            return redirect()->back()->with('message', 'Daftar penugasan berhasil diambil');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil daftar penugasan');
        }
    }

    public function calculateVerifiedFinalScore(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->calculateVerifiedFinalScore($request->verified_answers ?? [], $request->metadata ?? []);
            // RESPONSE
            return redirect()->back()->with('message', 'Skor verifikasi berhasil dihitung');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghitung skor verifikasi');
        }
    }

    public function finalizeReview(Request $request)
    {
        try {
            // DTO

            // SERVICE
            $this->reviewService->finalizeReview($request->submission_id);
            // RESPONSE
            return redirect()->back()->with('message', 'Review berhasil difinalisasi');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}

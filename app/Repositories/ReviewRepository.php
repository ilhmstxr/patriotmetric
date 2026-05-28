<?php

namespace App\Repositories;

use App\Models\Kategori;
use App\Models\Assessment;
use App\Models\ResponAssessment;
use App\Models\Pertanyaan;

class ReviewRepository extends BaseRepository
{
    public function __construct(Assessment $model)
    {
        parent::__construct($model);
    }

    public function assignReviewerToSubmissions($reviewerId, array $submissionIds)
    {
        return $this->model->whereIn('id', $submissionIds)->update([
            'reviewer_id' => $reviewerId
        ]);
    }

    public function getAssignedSubmissionsWithUser($reviewerId)
    {
        return $this->model->with('user')->where('reviewer_id', $reviewerId)->get();
    }

    public function getAnswerBySubmissionAndQuestion($submissionId, $questionId)
    {
        return ResponAssessment::where('assessment_id', $submissionId)
            ->where('question_id', $questionId)
            ->first();
    }

    public function hasUnverifiedAnswers($submissionId)
    {
        // Completeness Check (Zero-Gap Validation): Pengecekan silang antara total pertanyaan vs total jawaban yang sudah diberikan manual_score
        $totalQuestions = Pertanyaan::count();
        
        $verifiedAnswers = ResponAssessment::where('assessment_id', $submissionId)
            ->whereNotNull('manual_score')
            ->count();

        // Mengembalikan true jika ada indikator/pertanyaan yang tertinggal (belum diverifikasi dgn manual score)
        return $verifiedAnswers < $totalQuestions;
    }

    public function sumVerifiedScore($submissionId)
    {
        return \App\Models\ResponAssessment::where('assessment_id', $submissionId)
            ->sum('manual_score'); // Or whatever the valid fallback is
    }

    public function updateStatus($id, $status, $calculatedTotal)
    {
        $Assessment = $this->model->findOrFail($id);
        $Assessment->update([
            'status' => $status,
            'final_score' => $calculatedTotal,
            // (opsional: Timestamping jika direquire)
            'reviewed_at' => now(),
        ]);
        return $Assessment;
    }

    public function publishStatus($submissionId)
    {
        $Assessment = $this->model->findOrFail($submissionId);
        $Assessment->update([
            'status' => 'PUBLISHED'
        ]);
        return $Assessment;
    }

    public function getVerifiedAnswers($submissionId)
    {
        return ResponAssessment::where('assessment_id', $submissionId)
            ->where('skor_validasi_reviewer', '>', 0)
            ->get();
    }

    public function getAllWithProgress($submissionId)
    {
        return Kategori::withCount([
            'pertanyaans as questions_count',
            'jawabans as answers_count' => function ($query) use ($submissionId) {
                $query->where('assessment_id', $submissionId)
                      ->whereNotNull('skor_validasi_reviewer');
            }
        ])->get();
    }

    public function getByCategoryWithExistingAnswers($categoryId, $submissionId)
    {
        return Pertanyaan::where('category_id', $categoryId)
            ->with(['jawaban' => function($q) use ($submissionId) {
                $q->where('assessment_id', $submissionId);
            }])
            ->get();
    }

    public function getWithReviewerContext($subId, $catId)
    {
        return ResponAssessment::where('assessment_id', $subId)
            ->whereHas('question', function ($q) use ($catId) {
                $q->where('category_id', $catId);
            })
            ->with('question') // Include question options/guideline
            ->get();
    }

    public function updateReviewData($submissionId, array $verifications)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($submissionId, $verifications) {
            foreach ($verifications as $ver) {
                // Sesuai dokumen "AssessmentAnswer::where('id', $id)->update(['reviewer_scale' => $scale, 'manual_score' => $score, 'verified_at' => now()])"
                // Mengubah existing answer dari tabel respon_assessment
                ResponAssessment::where('id', $ver['id'])
                    ->where('assessment_id', $submissionId) // Mengunci untuk re-verify assessment yg tepat
                    ->update([
                        'reviewer_scale' => $ver['scale_choice'],
                        'manual_score' => $ver['manual_score'],
                        // Memastikan fallback/alias jika score validation yg utama di sistem ini disamakan:
                        'skor_validasi_reviewer' => $ver['manual_score'],
                        'verified_at' => now(), // Audit Trail
                    ]);
            }
        });
    }

    public function getAnswersByCategory($submissionId, $categoryId)
    {
        return ResponAssessment::where('assessment_id', $submissionId)
            ->whereHas('question', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })->get();
    }
}

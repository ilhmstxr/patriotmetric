<?php

namespace App\Repositories;

use App\Models\pengumpulan;
use App\Models\pengumpulan_jawaban;

class ReviewRepository extends BaseRepository
{
    public function __construct(pengumpulan $model)
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
        return pengumpulan_jawaban::where('submission_id', $submissionId)
            ->where('question_id', $questionId)
            ->first();
    }

    public function hasUnverifiedAnswers($submissionId)
    {
        // Completeness Check: Pengecekan silang antara total pertanyaan vs total jawaban yang sudah diverifikasi
        $totalQuestions = \App\Models\pertanyaan::count();
        
        $verifiedAnswers = pengumpulan_jawaban::where('submission_id', $submissionId)
            ->whereNotNull('skor_validasi_reviewer')
            ->count();

        // Mengembalikan true jika ada indikator/pertanyaan yang tertinggal
        return $verifiedAnswers < $totalQuestions;
    }

    public function sumVerifiedScore($submissionId)
    {
        return pengumpulan_jawaban::where('submission_id', $submissionId)
            ->sum('skor_validasi_reviewer');
    }

    public function updateStatusAndFinalScore($submissionId, $status, $totalScore)
    {
        $pengumpulan = $this->model->findOrFail($submissionId);
        $pengumpulan->update([
            'status' => $status,
            'total_skor_akhir' => $totalScore,
            // Timestamping (Final Lock Time)
            'reviewed_at' => now() 
        ]);
        return $pengumpulan;
    }

    public function getVerifiedAnswers($submissionId)
    {
        return pengumpulan_jawaban::where('submission_id', $submissionId)
            ->where('skor_validasi_reviewer', '>', 0)
            ->get();
    }

    public function getAllWithProgress($submissionId)
    {
        return \App\Models\kategori::withCount([
            'pertanyaans as questions_count',
            'jawabans as answers_count' => function ($query) use ($submissionId) {
                $query->where('submission_id', $submissionId)
                      ->whereNotNull('skor_validasi_reviewer');
            }
        ])->get();
    }

    public function getByCategoryWithExistingAnswers($categoryId, $submissionId)
    {
        return \App\Models\pertanyaan::where('category_id', $categoryId)
            ->with(['jawaban' => function($q) use ($submissionId) {
                $q->where('submission_id', $submissionId);
            }])
            ->get();
    }

    public function getWithReviewerContext($subId, $catId)
    {
        return \App\Models\pengumpulan_jawaban::where('submission_id', $subId)
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
                // Mengubah existing answer dari tabel pengumpulan_jawaban
                \App\Models\pengumpulan_jawaban::where('id', $ver['id'])
                    ->where('submission_id', $submissionId) // Mengunci untuk re-verify assessment yg tepat
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
        return \App\Models\pengumpulan_jawaban::where('submission_id', $submissionId)
            ->whereHas('question', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })->get();
    }
}

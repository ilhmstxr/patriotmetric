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
        return pengumpulan_jawaban::where('submission_id', $submissionId)
            ->whereNull('skor_validasi_reviewer')
            ->exists();
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
            'total_skor_akhir' => $totalScore
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

    public function upsertAnswers($submissionId, array $answers)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($submissionId, $answers) {
            foreach ($answers as $answer) {
                \App\Models\pengumpulan_jawaban::updateOrCreate(
                    [
                        'submission_id' => $submissionId,
                        'question_id' => $answer['question_id']
                    ],
                    [
                        'skor_validasi_reviewer' => $answer['skor_validasi_reviewer'] ?? null,
                        // review payload can include other verification components
                    ]
                );
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

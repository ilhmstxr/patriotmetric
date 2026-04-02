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
}

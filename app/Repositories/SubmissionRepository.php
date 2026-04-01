<?php

namespace App\Repositories;

use App\Models\pengumpulan;
use App\Models\pengumpulan_jawaban;

class SubmissionRepository extends BaseRepository
{
    /**
     * SubmissionRepository constructor.
     * Mengikat Model terkait ke BaseRepository.
     */
    public function __construct(pengumpulan $model)
    {
        parent::__construct($model);
    }

    public function getTaskDetailsWithRelations($submissionId)
    {
        return $this->model->with(['jawabans.pertanyaan', 'user', 'reviewer'])->find($submissionId);
    }

    public function updateOrCreateAnswer($submissionId, $questionId, array $data)
    {
        return pengumpulan_jawaban::updateOrCreate(
            [
                'submission_id' => $submissionId,
                'question_id' => $questionId
            ],
            $data
        );
    }

    public function hasEmptyEvidenceLink($submissionId)
    {
        return pengumpulan_jawaban::where('submission_id', $submissionId)
            ->where(function ($query) {
                $query->whereNull('tautan_bukti_drive')->orWhere('tautan_bukti_drive', '');
            })->exists();
    }

    public function sumSystemScore($submissionId)
    {
        return pengumpulan_jawaban::where('submission_id', $submissionId)
            ->sum('skor_sistem');
    }

    public function updateStatusAndScore($submissionId, $status, $totalScore)
    {
        $pengumpulan = $this->model->findOrFail($submissionId);
        $pengumpulan->update([
            'status' => $status,
            'total_skor_sistem' => $totalScore
        ]);
        return $pengumpulan;
    }
}

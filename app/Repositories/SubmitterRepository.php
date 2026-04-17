<?php

namespace App\Repositories;

use App\Models\pengumpulan;
use App\Models\pengumpulan_jawaban;
use App\Models\kategori;
use App\Models\pertanyaan;

class SubmitterRepository extends BaseRepository
{
    public function __construct(pengumpulan $model)
    {
        parent::__construct($model);
    }

    public function getAllWithProgress($assessmentId)
    {
        return kategori::withCount([
            'pertanyaans as questions_count',
            'jawabans as answers_count' => function ($query) use ($assessmentId) {
                $query->where('submission_id', $assessmentId);
            }
        ])->get();
    }

    public function getByCategoryWithExistingAnswers($categoryId, $assessmentId)
    {
        return pertanyaan::where('category_id', $categoryId)
            ->with(['jawaban' => function($q) use ($assessmentId) {
                $q->where('submission_id', $assessmentId);
            }])
            ->get();
    }

    public function upsertAnswers($assessmentId, array $answers)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($assessmentId, $answers) {
            foreach ($answers as $answer) {
                pengumpulan_jawaban::updateOrCreate(
                    [
                        'submission_id' => $assessmentId,
                        'question_id' => $answer['question_id']
                    ],
                    [
                        'claim_value' => $answer['claim_value'] ?? null,
                        'evidence_url' => $answer['evidence_url'] ?? null,
                    ]
                );
            }
        });
    }

    public function getAnswersByCategory($assessmentId, $categoryId)
    {
        return pengumpulan_jawaban::where('submission_id', $assessmentId)
            ->whereHas('question', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })->get();
    }

    public function updateStatus($id, $status)
    {
        return $this->model->where('id', $id)->update([
            'status' => $status,
            'submitted_at' => now()
        ]);
    }
}

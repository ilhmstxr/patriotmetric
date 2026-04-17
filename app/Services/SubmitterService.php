<?php

namespace App\Services;

use App\Repositories\SubmitterRepository;
use Exception;

/**
 * @property \App\Repositories\SubmitterRepository $repository
 */
class SubmitterService extends BaseService
{
    public function __construct(SubmitterRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getStepperProgress(\App\DTOs\SubmitterDTO $dto)
    {
        $categories = $this->repository->getAllWithProgress($dto->assessmentId);

        $progressData = [];
        foreach ($categories as $category) {
            $isCompleted = $category->questions_count > 0 && $category->questions_count === $category->answers_count;
            $progressData[] = [
                'category_id' => $category->id,
                'nama_kategori' => $category->nama_kategori,
                'questions_count' => $category->questions_count,
                'answers_count' => $category->answers_count,
                'status' => $isCompleted ? 'completed' : 'pending'
            ];
        }

        return $progressData;
    }

    public function getQuestionsWithAnswers(\App\DTOs\SubmitterDTO $dto)
    {
        $questions = $this->repository->getByCategoryWithExistingAnswers($dto->categoryId, $dto->assessmentId);
        
        if ($questions->isEmpty()) {
            throw new Exception("Kategori tidak ditemukan atau tidak memiliki soal.", 404);
        }

        return $questions;
    }

    public function persistProgress(\App\DTOs\SubmitterDTO $dto)
    {
        $assessment = $this->repository->find($dto->assessmentId);
        
        if ($assessment && in_array($assessment->status, ['SUBMITTED', 'REVIEWING', 'REVIEWED', 'PUBLISHED'])) {
            throw new Exception("Akses ditolak: Asesmen tidak bisa diubah karena sudah final atau sedang direview.", 403);
        }

        $sanitizedAnswers = [];
        foreach ($dto->answers as $ans) {
            $sanitizedAnswers[] = [
                'question_id' => $ans['indicator_id'] ?? $ans['question_id'],
                'claim_value' => $ans['claim_value'] ?? null,
                'evidence_url' => filter_var($ans['evidence_url'] ?? '', FILTER_SANITIZE_URL),
            ];
        }

        $this->repository->upsertAnswers($dto->assessmentId, $sanitizedAnswers);

        return true;
    }

    public function calculateCategoryPreview(\App\DTOs\SubmitterDTO $dto)
    {
        $answers = $this->repository->getAnswersByCategory($dto->assessmentId, $dto->categoryId);

        $totalEstimatedScore = 0;
        foreach ($answers as $answer) {
            // Logika pratinjau submitter (Sesuai dokumen: menjumlahkan berdasarkan bobot, konversi skala 1-5, dst)
            // Untuk sementara kita mengakumulasikan nilai mentah (bisa diganti rumusnya sesuai kebutuhan tabel pertanyaans - bobot)
            if (is_numeric($answer->claim_value)) {
                $totalEstimatedScore += $answer->claim_value;
            }
        }

        return [
            'estimated_score' => $totalEstimatedScore,
            'label' => "Estimasi Skala",
            'details' => "Skor ini bersifat estimasi kasar."
        ];
    }

    public function lockAssessment(\App\DTOs\SubmitterDTO $dto)
    {
        $totalQuestions = \App\Models\pertanyaan::count();
        $answered = \App\Models\pengumpulan_jawaban::where('submission_id', $dto->assessmentId)
            ->whereNotNull('claim_value') // Anggap jawaban valid jika ada claim value
            ->count();

        if ($answered < $totalQuestions) {
            throw new Exception("Validasi kelengkapan gagal: Ada soal yang belum terjawab di kategori.", 422);
        }

        return $this->repository->updateStatus($dto->assessmentId, 'SUBMITTED');
    }
}

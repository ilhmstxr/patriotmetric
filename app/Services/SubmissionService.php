<?php

namespace App\Services;

use App\Repositories\SubmissionRepository;
use App\Traits\CalculatesRubrikScore;
use Exception;

/**
 * @property \App\Repositories\SubmissionRepository $repository
 */
class SubmissionService extends BaseService
{
    use CalculatesRubrikScore;

    /**
     * SubmissionService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(SubmissionRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Mengambil data pertanyaan sekaligus jawaban yang sudah diisi oleh peserta untuk ditampilkan kembali pada form.
     */
    public function getTaskDetails($submissionId)
    {
        return $this->repository->getTaskDetailsWithRelations($submissionId);
    }

     public function getPreviewScore(String $submissionId)
    {
       return $this->repository->getSubmitterAnswers($submissionId);
    }
    /**
     * Menyimpan jawaban secara masal (bulk) ke dalam tabel jawaban setiap kali terjadi autosave di frontend.
     */
    public function saveDraft(array $answers)
    {
        foreach ($answers as $answer) {
            $this->repository->updateOrCreateAnswer(
                $answer['submission_id'],
                $answer['question_id'],
                [
                    'jawaban_teks' => $answer['jawaban_teks'] ?? null,
                    'tautan_bukti_drive' => $answer['tautan_bukti_drive'] ?? null,
                    'skor_sistem' => $answer['skor_sistem'] ?? 0
                ]
            );
        }

        return true;
    }

    // BUG
    /**
     * Menggunakan CalculatesRubrikScore untuk memberikan estimasi skor real-time kepada peserta sebelum data dikunci.
     */
    public function calculateLivePreview(array $answers, array $metadata)
    {
        $finalScore = 0;

        // Asumsi answers di-group by category_id atau category_name
        // Logika detail implementasi perhitungan sesuai struktur (disederhanakan untuk demonstrasi)
        foreach ($metadata as $categoryName => $data) {
            $subtotal = $this->calculateCategorySubtotal($answers[$categoryName] ?? [], $categoryName);
            $maxScore = $data['jumlah_indikator'] * 5;
            $finalScore += $this->applyCategoryWeight($subtotal, $data['bobot'], $maxScore);
        }

        return [
            'estimated_score' => round($finalScore, 2)
        ];
    }

    // BUG
    /**
     * Memvalidasi apakah semua indikator wajib dan tautan bukti (link drive) sudah terisi.
     */
    public function checkCompletionStatus($submissionId)
    {
        return !$this->repository->hasEmptyEvidenceLink($submissionId);
    }

    // BUG: lock submission by frontend 
    /**
     * Melakukan finalisasi, menghitung skor self-assessment akhir, dan mengubah status menjadi LOCKED (mengunci akses edit).
     */
    public function lockSubmission($submissionId)
    {
        if (!$this->checkCompletionStatus($submissionId)) {
            throw new Exception("Semua indikator wajib dan tautan bukti harus terisi sebelum submit.");
        }

        $totalSkorSistem = $this->repository->sumSystemScore($submissionId);

        return $this->repository->updateStatusAndScore($submissionId, 'LOCKED', $totalSkorSistem);
    }
}

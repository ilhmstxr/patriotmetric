<?php

namespace App\Services;

use App\Repositories\ReviewRepository;
use App\Traits\CalculatesRubrikScore;
use Exception;

/**
 * @property \App\Repositories\ReviewRepository $repository
 */
class ReviewService extends BaseService
{
    use CalculatesRubrikScore;

    public function __construct(ReviewRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Melakukan plotting atau pembagian jatah peserta kepada reviewer tertentu.
     */
    public function assignReviewersToSubmissions($reviewerId, array $submissionIds)
    {
        return $this->repository->assignReviewerToSubmissions($reviewerId, $submissionIds);
    }

    /**
     * Menampilkan daftar peserta yang harus dikoreksi oleh reviewer yang sedang login.
     */
    public function getAssignedSubmissions($reviewerId)
    {
        return $this->repository->getAssignedSubmissionsWithUser($reviewerId);
    }

    /**
     * Menyimpan hasil verifikasi skor dan catatan perbaikan untuk satu indikator tertentu.
     */
    // BUG
    public function verifySingleIndicator($submissionId, $indicatorId, $verifiedScore, $notes = null)
    {
        $jawaban = $this->repository->getAnswerBySubmissionAndQuestion($submissionId, $indicatorId);

        if ($jawaban) {
            $jawaban->update([
                'skor_validasi_reviewer' => $verifiedScore,
                // 'catatan_perbaikan' => $notes // asumsi jika ada field ini
            ]);
            return $jawaban;
        }

        throw new Exception("Jawaban indikator tidak ditemukan.");
    }

    /**
     * Menggunakan CalculatesRubrikScore untuk menghitung skor total berdasarkan angka yang telah divalidasi oleh reviewer.
     */
    public function calculateVerifiedFinalScore(array $verifiedAnswers, array $metadata)
    {
        $finalScore = 0;

        foreach ($metadata as $categoryName => $data) {
            $subtotal = $this->calculateCategorySubtotal($verifiedAnswers[$categoryName] ?? [], $categoryName);
            $maxScore = $data['jumlah_indikator'] * 5;
            $finalScore += $this->applyCategoryWeight($subtotal, $data['bobot'], $maxScore);
        }

        return round($finalScore, 2);
    }


    /**
     * menampilkan hasil fix peniliaian pengerjaan
     */
    public function getFinalScore(String $submissionId)
    {
        return $this->repository->getVerifiedAnswers($submissionId);
    }



    /**
     * Memastikan semua indikator telah diperiksa, menyimpan skor akhir hasil verifikasi, dan mengubah status menjadi REVIEWED.
     */
    public function finalizeReview($submissionId)
    {
        $belumDinilai = $this->repository->hasUnverifiedAnswers($submissionId);

        if ($belumDinilai) {
            throw new Exception("Ada indikator yang belum diverifikasi oleh reviewer.");
        }

        // Idealnya, $this->calculateVerifiedFinalScore dipanggil di sini dengan data answers dan metadata.
        // Untuk saat ini, kita bisa menggunakan repository fallback jika logika answers belum di-fetch:
        $totalSkorAkhir = $this->repository->sumVerifiedScore($submissionId);

        return $this->repository->updateStatusAndFinalScore($submissionId, 'REVIEWED', $totalSkorAkhir);
    }

    /**
     * Mengambil daftar kategori (Stepper) dan status progres verifikasi.
     */
    public function getStepperProgress(\App\DTOs\ReviewDTO $dto)
    {
        $categories = $this->repository->getAllWithProgress($dto->submissionId);

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
}

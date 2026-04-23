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
     * Memastikan semua indikator telah diperiksa, menghitung total nilai akhir (weighted average), dan mengubah status menjadi REVIEWED.
     */
    public function lockReview(\App\DTOs\ReviewDTO $dto)
    {
        // Pengecekan Zero-Gap: Cek apakah SEMUA indikator sudah diberikan manual_score
        $belumLengkap = $this->repository->hasUnverifiedAnswers($dto->submissionId);

        if ($belumLengkap) {
            // Jika ada satu saja soal yang belum divrifikasi, gagalkan.
            throw new \Exception("Validasi gagal: Ada indikator/soal yang belum diberikan manual_score.", 422);
        }

        // Scoring Calculation: Kalkulasi total nilai akhir (weighted average) dari seluruh kategori.
        // Di sini kita bisa mengambil seluruh verifikasi dan metadata bobot. Untuk saat ini kita asumsikan menggunakan penjumlahan repo dasar.
        $calculatedTotal = $this->repository->sumVerifiedScore($dto->submissionId);

        // Update status menjadi REVIEWED dan menyimpan kalkulasi ke kolom final_score
        return $this->repository->updateStatus($dto->submissionId, 'REVIEWED', $calculatedTotal);
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

    /**
     * Mengambil data perbandingan (Klaim Peserta vs Input Reviewer) per kategori.
     */
    public function getComparisonData(\App\DTOs\ReviewDTO $dto)
    {
        $comparisonData = $this->repository->getWithReviewerContext($dto->submissionId, $dto->categoryId);

        if ($comparisonData->isEmpty()) {
            throw new \Exception("Data tidak ditemukan untuk kategori tersebut.", 404);
        }

        return $comparisonData;
    }

    /**
     * Menyimpan hasil verifikasi (Pilihan Skala & Skor Manual) per kategori.
     */
    public function persistVerification(\App\DTOs\ReviewDTO $dto)
    {
        // Cek status asesmen: Jika dinilai sudah selesai, cegah perubahan opsional.
        $submission = $this->repository->find($dto->submissionId);

        if ($submission && $submission->status === 'REVIEWED') {
            throw new \Exception("Akses ditolak: Review untuk institusi ini sudah final (REVIEWED).", 403);
        }

        // Sanitasi dan memvalidasi batas wajar nilai
        $sanitizedVerifications = [];
        foreach ($dto->answers as $ver) {
            $manualScore = isset($ver['manual_score']) ? floatval($ver['manual_score']) : null;
            $scaleChoice = isset($ver['scale_choice']) ? intval($ver['scale_choice']) : null;

            // Logika validasi batas wajar manual score berdasarkan skema bisa ditambahkan di sini, misalnya:
            // if ($scaleChoice && ($manualScore > ($scaleChoice * 20))) throw Exception...

            $sanitizedVerifications[] = [
                'id' => $ver['id'], // ID dari pengumpulan_jawaban
                'scale_choice' => $scaleChoice,
                'manual_score' => $manualScore,
            ];
        }

        // Simpan beramai-ramai sekaligus dengan DB Transaction (atau satuan update)
        $this->repository->updateReviewData($dto->submissionId, $sanitizedVerifications);

        // Update status asesmen menjadi REVIEWING 
        if ($submission && $submission->status !== 'REVIEWING') {
            $submission->update(['status' => 'REVIEWING']);
        }

        return true;
    }

    /**
     * Hitung estimasi skor khusus untuk di satu kategori saja (skor reviewer).
     */
    public function calculateCategoryPreview(\App\DTOs\ReviewDTO $dto)
    {
        $answers = $this->repository->getAnswersByCategory($dto->submissionId, $dto->categoryId);

        // Anggap total bobot sudah dikonversi atau ini merupakan estimasi kumulatif
        $totalEstimatedScore = 0;
        foreach ($answers as $answer) {
            if ($answer->skor_validasi_reviewer !== null) {
                $totalEstimatedScore += $answer->skor_validasi_reviewer;
            }
        }

        return [
            'estimated_score' => $totalEstimatedScore,
            'label' => "Estimasi Skor Kategori: " . $totalEstimatedScore,
            'details' => "Skor ini bersifat estimasi dari hasil verifikasi."
        ];
    }

    /**
     * Mempublikasikan penilaian yang telah selesai.
     */
    public function publishAssessment(\App\DTOs\ReviewDTO $dto)
    {
        $submission = $this->repository->find($dto->submissionId);

        if (!$submission || $submission->status !== 'REVIEWED') {
            throw new \Exception("Hanya institusi dengan status 'REVIEWED' yang bisa dipublikasikan.", 422);
        }

        // Service -> Repository
        return $this->repository->publishStatus($dto->submissionId);
    }
}

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
     * Memastikan semua indikator telah diperiksa, menyimpan skor akhir hasil verifikasi, dan mengunci (Final Lock).
     */
    public function finalizeReview(\App\DTOs\ReviewDTO $dto)
    {
        // Validasi kelengkapan: Cek apakah ada soal yang belum diverifikasi
        $belumLengkap = $this->repository->hasUnverifiedAnswers($dto->submissionId);

        if ($belumLengkap) {
            // Jika belum lengkap, lempar 422 Unprocessable Entity
            throw new \Exception("Validasi kelengkapan gagal: Pastikan semua soal di seluruh kategori telah diverifikasi.", 422);
        }

        // Idealnya, $this->calculateVerifiedFinalScore dipanggil di sini dengan data answers dan metadata.
        // Untuk saat ini, kita menggunakan akumulasi dari repository:
        $totalSkorAkhir = $this->repository->sumVerifiedScore($dto->submissionId);

        // Update status menjadi REVIEWED (Immutable State) dan merekam timestamp
        return $this->repository->updateStatusAndFinalScore($dto->submissionId, 'REVIEWED', $totalSkorAkhir);
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
     * Mengambil soal kategori tertentu beserta jawaban yang ada di submission (pre-filled).
     */
    public function getQuestionsWithAnswers(\App\DTOs\ReviewDTO $dto)
    {
        // Validasi jika kategori tidak ada bisa dilakukan di sini 
        // dengan cek ke database atau biarkan kosong jika query return []
        
        $questions = $this->repository->getByCategoryWithExistingAnswers($dto->categoryId, $dto->submissionId);
        
        if ($questions->isEmpty()) {
            throw new \Exception("Kategori tidak ditemukan atau tidak memiliki soal.", 404);
        }

        return $questions;
    }

    /**
     * Menyimpan progres jawaban/verifikasi per kategori secara Atomic.
     */
    public function persistProgress(\App\DTOs\ReviewDTO $dto)
    {
        // Cek status asesmen: Jika dinilai sudah selesai, cegah perubahan opsional.
        $submission = $this->repository->find($dto->submissionId);
        
        if ($submission && $submission->status === 'REVIEWED') {
            throw new \Exception("Akses ditolak: Review untuk institusi ini sudah final (REVIEWED).", 403);
        }

        // Sanitasi/membersihkan nilai (misal URL bukti untuk Reviewer, meskipun mereka lebih fokus validasi)
        $sanitizedAnswers = [];
        foreach ($dto->answers as $ans) {
            $sanitizedAnswers[] = [
                'question_id' => $ans['question_id'],
                'skor_validasi_reviewer' => isset($ans['skor_validasi_reviewer']) ? floatval($ans['skor_validasi_reviewer']) : null,
                // reviewer tidak menyumbang evidence_url melainkan melihatnya, jd tidak perlu sanitasi URL
            ];
        }

        // Simpan beramai-ramai sekaligus dengan UpdateOrCreate DB Transaction
        $this->repository->upsertAnswers($dto->submissionId, $sanitizedAnswers);

        // Jika statusnya belum berada di 'REVIEWING', ubah ke REVIEWING 
        // (artinya Reviewer sudah menyicil pengerjaan)
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
}

<?php

namespace App\Services;

use App\Repositories\ReviewRepository;
use Exception;

/**
 * @property \App\Repositories\ReviewRepository $repository
 */
class ReviewService extends BaseService
{
    public function __construct(ReviewRepository $repository)
    {
        parent::__construct($repository);
    }

    public function assignReviewer($reviewerId, array $submissionIds)
    {
        return $this->repository->assignReviewerToSubmissions($reviewerId, $submissionIds);
    }

    // Mengambil daftar peserta yang menjadi jatah reviewer tersebut
    public function getAssignedSubmissions($reviewerId)
    {
        return $this->repository->getAssignedSubmissionsWithUser($reviewerId);
    }

    // Verifikasi Skor per Indikator
    public function verifyIndicatorScore($submissionId, $indicatorId, $verifiedScore, $notes = null) {
        $jawaban = $this->repository->getAnswerBySubmissionAndQuestion($submissionId, $indicatorId);

        if ($jawaban) {
            $jawaban->update([
                'skor_validasi_reviewer' => $verifiedScore,
            ]);
            return $jawaban;
        }

        throw new Exception("Jawaban indikator tidak ditemukan.");
    }

    // Lock Review (Finalisasi nilai oleh reviewer)
    public function finalizeReview($submissionId) {
        // 1. Pastikan semua indikator sudah diberi nilai verifikasi
        $belumDinilai = $this->repository->hasUnverifiedAnswers($submissionId);

        if ($belumDinilai) {
            throw new Exception("Ada indikator yang belum diverifikasi oleh reviewer.");
        }

        // 3. Hitung skor akhir berdasarkan verifikasi reviewer
        $totalSkorAkhir = $this->repository->sumVerifiedScore($submissionId);

        // 2. Update status submission ke 'REVIEWED'
        return $this->repository->updateStatusAndFinalScore($submissionId, 'REVIEWED', $totalSkorAkhir);
    }
}

<?php

namespace App\Services;

use App\Repositories\ReviewRepository;
use App\Models\pengumpulan;
use App\Models\pengumpulan_jawaban;
use Exception;

class ReviewService extends BaseService
{
    public function __construct(ReviewRepository $repository)
    {
        parent::__construct($repository);
    }

    public function assignReviewer($reviewerId, array $submissionIds)
    {
        return pengumpulan::whereIn('id', $submissionIds)->update([
            'reviewer_id' => $reviewerId
        ]);
    }

    // Mengambil daftar peserta yang menjadi jatah reviewer tersebut
    public function getAssignedSubmissions($reviewerId)
    {
        return pengumpulan::with('user')->where('reviewer_id', $reviewerId)->get();
    }

    // Verifikasi Skor per Indikator
    public function verifyIndicatorScore($submissionId, $indicatorId, $verifiedScore, $notes = null) {
        $jawaban = pengumpulan_jawaban::where('submission_id', $submissionId)
            ->where('question_id', $indicatorId)
            ->first();

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
        $belumDinilai = pengumpulan_jawaban::where('submission_id', $submissionId)
            ->whereNull('skor_validasi_reviewer')
            ->exists();

        if ($belumDinilai) {
            throw new Exception("Ada indikator yang belum diverifikasi oleh reviewer.");
        }

        // 3. Hitung skor akhir berdasarkan verifikasi reviewer
        $totalSkorAkhir = pengumpulan_jawaban::where('submission_id', $submissionId)
            ->sum('skor_validasi_reviewer');

        // 2. Update status submission ke 'REVIEWED'
        $pengumpulan = pengumpulan::findOrFail($submissionId);
        $pengumpulan->update([
            'status' => 'REVIEWED',
            'total_skor_akhir' => $totalSkorAkhir
        ]);

        return $pengumpulan;
    }
}

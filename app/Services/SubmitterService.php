<?php

namespace App\Services;

use App\Repositories\SubmitterRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use App\DTOs\SubmitterDTO;

/**
 * @property \App\Repositories\SubmitterRepository $repository
 */
class SubmitterService extends BaseService
{
    public function __construct(SubmitterRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * CORE SECURITY: Helper untuk mencari Assessment ID yang sedang aktif milik user.
     * Mencegah user memodifikasi data institusi lain.
     */
    private function getActiveAssessmentOrFail($userId)
    {
        $assessment = $this->repository->findActiveByUserId($userId);

        if (!$assessment) {
            throw new Exception("Sesi asesmen aktif tidak ditemukan untuk periode ini. Pastikan baseline Anda sudah terverifikasi.", 404);
        }

        return $assessment;
    }

    /**
     * 1. Ambil Semua Pertanyaan & Jawaban (Single Form)
     */
    public function getAllQuestionsWithAnswers(SubmitterDTO $dto)
    {
        $assessment = $this->getActiveAssessmentOrFail($dto->userId);

        // Repository mengambil seluruh soal, beserta relasi opsi jawaban
        // dan jawaban tersimpan (jika ada) khusus untuk assessment_id ini.
        $questions = $this->repository->getAllQuestionsWithExistingAnswers($assessment->id);

        if ($questions->isEmpty()) {
            throw new Exception("Master data soal belum tersedia.", 404);
        }

        return [
            'assessment_id' => $assessment->id,
            'status' => $assessment->status,
            'questions' => $questions
        ];
    }

    /**
     * 2. Auto-Save Progress (Simpan periodik / Single Form)
     */
    public function autoSaveProgress(SubmitterDTO $dto)
    {
        $assessment = $this->getActiveAssessmentOrFail($dto->userId);

        // Pagar Keamanan: Tolak jika sudah dikunci
        if (in_array($assessment->status, ['SUBMITTED', 'REVIEWING', 'REVIEWED', 'PUBLISHED'])) {
            throw new Exception("Akses ditolak: Asesmen tidak bisa diubah karena sudah dikunci (Final).", 403);
        }

        // Sanitasi Data
        $sanitizedAnswers = [];
        foreach ($dto->answers as $ans) {
            // Abaikan jika data kosong (membantu payload auto-save yang mungkin parsial)
            if (!isset($ans['indicator_id'])) continue;

            $sanitizedAnswers[] = [
                'question_id' => $ans['indicator_id'],
                'claim_value' => $ans['claim_value'] ?? null,
                'evidence_url' => filter_var($ans['evidence_url'] ?? '', FILTER_SANITIZE_URL),
                'assessment_id' => $assessment->id // Inject ID internal, bukan dari request
            ];
        }

        // Lakukan Upsert di level Repository
        $this->repository->upsertAnswers($assessment->id, $sanitizedAnswers);

        return true;
    }

    /**
     * 3. Final Lock (Validasi dan Penguncian)
     */
    public function lockAssessment(SubmitterDTO $dto)
    {
        $assessment = $this->getActiveAssessmentOrFail($dto->userId);

        $totalQuestions = $this->repository->countTotalMandatoryQuestions();
        $answered = $this->repository->countValidAnswers($assessment->id);

        if ($answered < $totalQuestions) {
            throw new Exception("Validasi kelengkapan gagal: Anda baru menjawab {$answered} dari {$totalQuestions} indikator. Harap lengkapi semua sebelum Final Submit.", 422);
        }

        // Kunci Data
        return $this->repository->updateStatus($assessment->id, 'SUBMITTED');
    }

    /**
     * 4. Hitung Estimasi Total (Preview Keseluruhan Form)
     */
    public function calculateTotalPreview(SubmitterDTO $dto)
    {
        $assessment = $this->getActiveAssessmentOrFail($dto->userId);
        $answers = $this->repository->getAllAnswersByAssessment($assessment->id);

        $totalEstimatedScore = 0;
        foreach ($answers as $answer) {
            // TODO: Ganti dengan formula baku PatriotMetric (Normalisasi/Bobot)
            if (is_numeric($answer->claim_value)) {
                $totalEstimatedScore += $answer->claim_value;
            }
        }

        return [
            'estimated_score' => $totalEstimatedScore,
            'label' => "Estimasi Skor Kasar",
            'details' => "Skor ini bersifat estimasi sebelum verifikasi Reviewer."
        ];
    }

    /**
     * 5. Ambil Progres Saat Ini (Untuk UI Progress Bar)
     */
    public function getCurrentProgress(SubmitterDTO $dto)
    {
        $assessment = $this->getActiveAssessmentOrFail($dto->userId);

        $totalQuestions = $this->repository->countTotalMandatoryQuestions();
        $answered = $this->repository->countValidAnswers($assessment->id);

        $percentage = $totalQuestions > 0 ? round(($answered / $totalQuestions) * 100) : 0;

        return [
            'total_questions' => $totalQuestions,
            'answered_questions' => $answered,
            'percentage' => $percentage,
            'is_completed' => $answered >= $totalQuestions
        ];
    }
}

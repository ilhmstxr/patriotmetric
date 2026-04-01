<?php

namespace App\Services;

use App\Repositories\SubmissionRepository;
use Exception;

/**
 * @property \App\Repositories\SubmissionRepository $repository
 */
class SubmissionService extends BaseService
{
    /**
     * SubmissionService constructor.
     * Otomatis melakukan injection Repository terkait.
     */
    public function __construct(SubmissionRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getTaskDetails($submissionId)
    {
        return $this->repository->getTaskDetailsWithRelations($submissionId);
    }

    // Menyimpan draft (autosave) tanpa mengunci
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

    public function isComplete($submissionId)
    {
        return !$this->repository->hasEmptyEvidenceLink($submissionId);
    }

    // Validasi kelengkapan & Mengunci Jawaban
    public function lockSubmission($submissionId)
    {
        // 1. Cek apakah semua 'evidence_link' dan 'selected_option' sudah terisi
        if (!$this->isComplete($submissionId)) {
            throw new Exception("Semua indikator harus diisi sebelum submit.");
        }

        // 3. Generate Final Score (Self-Assessment)
        $totalSkorSistem = $this->repository->sumSystemScore($submissionId);

        // 2. Update status ke 'LOCKED'
        return $this->repository->updateStatusAndScore($submissionId, 'LOCKED', $totalSkorSistem);
    }
}

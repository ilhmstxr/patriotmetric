<?php

namespace App\Services;

use App\Repositories\SubmissionRepository;
use App\Models\pengumpulan;
use App\Models\pengumpulan_jawaban;
use Exception;

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
        return pengumpulan::with(['jawabans.pertanyaan', 'user', 'reviewer'])->find($submissionId);
    }

    // Menyimpan draft (autosave) tanpa mengunci
    public function saveDraft(array $answers)
    {
        foreach ($answers as $answer) {
            pengumpulan_jawaban::updateOrCreate(
                [
                    'submission_id' => $answer['submission_id'],
                    'question_id' => $answer['question_id']
                ],
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
        // Cek apakah ada jawaban yang kosong pada tautan bukti
        $jawabanKosong = pengumpulan_jawaban::where('submission_id', $submissionId)
            ->where(function ($query) {
                $query->whereNull('tautan_bukti_drive')->orWhere('tautan_bukti_drive', '');
            })->exists();

        return !$jawabanKosong;
    }

    // Validasi kelengkapan & Mengunci Jawaban
    public function lockSubmission($submissionId)
    {
        // 1. Cek apakah semua 'evidence_link' dan 'selected_option' sudah terisi
        if (!$this->isComplete($submissionId)) {
            throw new Exception("Semua indikator harus diisi sebelum submit.");
        }

        // 3. Generate Final Score (Self-Assessment)
        $totalSkorSistem = pengumpulan_jawaban::where('submission_id', $submissionId)
            ->sum('skor_sistem');

        // 2. Update status ke 'LOCKED'
        $pengumpulan = pengumpulan::findOrFail($submissionId);
        $pengumpulan->update([
            'status' => 'LOCKED',
            'total_skor_sistem' => $totalSkorSistem
        ]);

        return $pengumpulan;
    }
}

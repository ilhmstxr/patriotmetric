<?php

namespace App\DTO\SubmitterDTO;

class JawabanDTO
{
    public readonly int $submissionId;
    public readonly int $pertanyaanId;
    public readonly ?int $jawabanId;
    public readonly ?string $jawabanTeks;
    public readonly ?string $tautanBukti;
    public readonly ?string $noteReviewer;

    public function __construct(int $submissionId, array $validatedData)
    {
        $this->submissionId = $submissionId;
        
        // Mapping otomatis dari array hasil validated request
        $this->pertanyaanId = $validatedData['pertanyaan_id'];
        $this->jawabanId    = $validatedData['jawaban_id'] ?? null;
        $this->jawabanTeks  = $validatedData['jawaban_teks'] ?? null;
        $this->tautanBukti  = $validatedData['tautan_bukti'] ?? null;
        $this->noteReviewer = $validatedData['note_reviewer'] ?? null;
    }
}
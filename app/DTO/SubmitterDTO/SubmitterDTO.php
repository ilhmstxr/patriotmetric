<?php

namespace App\DTO\SubmitterDTO;

class SubmitterDTO
{

    public readonly int $userId;
    public ?int $assessmentId = null; // Tambahkan ini, boleh null karena awalnya belum tahu ID pengumpulannya

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}

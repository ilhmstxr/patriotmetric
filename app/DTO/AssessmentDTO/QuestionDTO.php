<?php

namespace App\DTO\AssessmentDTO;

class QuestionDTO
{
    // Menggunakan readonly agar data kebal terhadap modifikasi liar di tengah jalan
    public readonly int $assessmentId;

    public function __construct(int $assessmentId)
    {
        $this->assessmentId = $assessmentId;
    }
}

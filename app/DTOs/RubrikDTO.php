<?php

namespace App\DTOs;

class RubrikDTO extends BaseDTO
{
    public array $kebijakan;
    public array $kelembagaan;
    public array $patriotisme;

    public function __construct(array $kebijakan = [], array $kelembagaan = [], array $patriotisme = [])
    {
        $this->kebijakan = $kebijakan;
        $this->kelembagaan = $kelembagaan;
        $this->patriotisme = $patriotisme;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['kebijakan'] ?? [],
            $data['kelembagaan'] ?? [],
            $data['patriotisme'] ?? []
        );
    }
}

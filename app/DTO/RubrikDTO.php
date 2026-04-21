<?php

namespace App\DTOs;

class RubrikDTO extends BaseDTO
{
    public function __construct(
        public array $kebijakan,
        public array $kelembagaan,
        public array $patriotisme
    ) {}

    public static function fromArray(array $data): self
    {
        // Di sini kita bisa memastikan data selalu berupa array yang valid
        return new self(
            $data['kebijakan'] ?? [],
            $data['kelembagaan'] ?? [],
            $data['patriotisme'] ?? []
        );
    }
}

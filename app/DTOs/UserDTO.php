<?php

namespace App\DTOs;

readonly class UserDTO
{
    /**
     * Data Transfer Object untuk User.
     * Menggunakan Constructor Property Promotion (Laravel 12/PHP 8.2+).
     */
    public function __construct(
        public array $data,
    ) {}

    public static function formRequest($request): self
    {
        return new self($request->validated());
    }
}

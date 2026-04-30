<?php

namespace App\DTO\AuthDTO;

readonly class LoginDTO
{
    public string $email;
    public string $password;

    public function __construct(array $validated)
    {
        $this->email = $validated['email'];
        $this->password = $validated['password'];
    }
}

<?php

namespace App\DTOs;

class RegisterUserDTO
{
    // Mas maganda kung ganito ang format sa PHP 8.2+
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $permission,

    ) {}

    // Magdagdag tayo ng static method para safe ang pag-map
    public static function fromRequest($validatedData): self
    {
        return new self(
            name: $validatedData['name'],
            email: $validatedData['email'],
            password: $validatedData['password'],
            permission: $validatedData['permission'],
        );
    }
}
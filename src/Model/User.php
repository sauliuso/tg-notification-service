<?php

declare(strict_types=1);

namespace App\Model;

final class User
{
    private int $id;

    private ?string $email;

    private ?string $phoneNumber;

    public function __construct(int $id, ?string $email, ?string $phoneNumber)
    {
        $this->id = $id;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
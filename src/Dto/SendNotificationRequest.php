<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class SendNotificationRequest
{
    #[Assert\NotBlank()]
    public int $userId;

    #[Assert\Choice(choices: ['sms', 'email', 'push'], multiple: true)]
    public array $channels;

    #[Assert\NotBlank()]
    public string $body;

    public ?string $title = null;
}
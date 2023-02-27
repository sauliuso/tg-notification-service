<?php

namespace App\ServiceProvider;

use App\Dto\SendNotificationRequest;
use App\Entity\Notification;
use App\Model\User;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.service_provider.strategy')]
interface ChannelStrategyInterface
{
    public function getChannel(): string;

    public function addAdapter(AdapterInterface $adapter): void;

    public function setUser(User $user): void;

    public function setRequestDto(SendNotificationRequest $dto): void;

    public function setNotification(Notification $notification): void;

    /**
     * @throws AdapterException
     */
    public function send(): array;
}
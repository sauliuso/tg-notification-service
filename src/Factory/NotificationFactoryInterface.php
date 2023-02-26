<?php

namespace App\Factory;

use App\Dto\SendNotificationRequest;
use App\Entity\Notification;
use App\Model\User;

interface NotificationFactoryInterface
{
    public function create(User $user, SendNotificationRequest $request, string $channel): Notification;
}
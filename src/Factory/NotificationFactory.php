<?php
declare(strict_types=1);

namespace App\Factory;

use App\Dto\SendNotificationRequest;
use App\Entity\Notification;
use App\Model\User;

final class NotificationFactory implements NotificationFactoryInterface
{
    public function create(User $user, SendNotificationRequest $request, string $channel): Notification
    {
        $notification = new Notification();
        $notification
            ->setChannel($channel)
            ->setUserId($user->getId())
            ->setStatus(Notification::STATUS_PENDING)
            ->setPayload((array) $request);

        return $notification;
    }
}
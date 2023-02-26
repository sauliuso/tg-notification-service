<?php
declare(strict_types=1);

namespace App\Message;

final class SendNotification
{
    private int $notificationId;

    public function __construct(int $notificationId)
    {
        $this->notificationId = $notificationId;
    }

   public function getNotificationId(): int
   {
       return $this->notificationId;
   }
}

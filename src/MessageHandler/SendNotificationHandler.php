<?php

namespace App\MessageHandler;

use App\Message\SendNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendNotificationHandler implements MessageHandlerInterface
{
    public function __invoke(SendNotification $message)
    {
        // do something with your message
        dump('processing notification', $message->getNotificationId());
    }
}

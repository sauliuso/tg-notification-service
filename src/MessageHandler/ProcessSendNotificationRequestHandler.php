<?php

namespace App\MessageHandler;

use App\Message\ProcessSendNotificationRequest;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProcessSendNotificationRequestHandler
{
    public function __invoke(ProcessSendNotificationRequest $message)
    {
        // TO DO:
        // - resolve user
        // - fan out request by channels
        // - validate user has contact info required by the channel
        // - persist notification record in DB
        // - trigger async processing for the notification
    }
}
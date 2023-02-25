<?php

namespace App\Message;

use App\Dto\SendNotificationRequest;

final class ProcessSendNotificationRequest
{
    private SendNotificationRequest $request;

    public function __construct(SendNotificationRequest $request)
    {
        $this->request = $request;
    }

    public function getRequest(): SendNotificationRequest
    {
        return $this->request;
    }
}
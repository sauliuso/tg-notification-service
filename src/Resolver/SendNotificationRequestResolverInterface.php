<?php
declare(strict_types=1);

namespace App\Resolver;

use App\Dto\SendNotificationRequest;

interface SendNotificationRequestResolverInterface
{
    public function resolveDtoFromArray(array $payload): SendNotificationRequest;
}
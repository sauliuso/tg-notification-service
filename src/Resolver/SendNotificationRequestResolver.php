<?php
declare(strict_types=1);

namespace App\Resolver;

use App\Dto\SendNotificationRequest;
use Symfony\Component\Serializer\SerializerInterface;

final class SendNotificationRequestResolver implements SendNotificationRequestResolverInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function resolveDtoFromArray(array $payload): SendNotificationRequest
    {
        return $this->serializer->deserialize(json_encode($payload), SendNotificationRequest::class, 'json');
    }
}
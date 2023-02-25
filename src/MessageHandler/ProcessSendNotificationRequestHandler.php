<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ProcessSendNotificationRequest;
use App\Resolver\UserResolverInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
final class ProcessSendNotificationRequestHandler
{
    private UserResolverInterface $userResolver;

    public function __construct(UserResolverInterface $userResolver)
    {
        $this->userResolver = $userResolver;
    }

    public function __invoke(ProcessSendNotificationRequest $message)
    {
        $request = $message->getRequest();
        $user = $this->userResolver->resolveById($request->userId);
        if ($user === null) {
            throw new UnrecoverableMessageHandlingException(sprintf('Could not resolve user by ID %d', $request->userId));
        }

        // TO DO:
        // - fan out request by channels
        // - validate user has contact info required by the channel
        // - persist notification record in DB
        // - trigger async processing for the notification

        dump('Processed user', $user);
    }
}
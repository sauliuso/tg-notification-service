<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Factory\NotificationFactoryInterface;
use App\Message\ProcessSendNotificationRequest;
use App\Message\SendNotification as SendNotificationMessage;
use App\Model\User;
use App\Repository\NotificationRepository;
use App\Resolver\SupportedChannelsResolverInterface;
use App\Resolver\UserResolverInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class ProcessSendNotificationRequestHandler
{
    private UserResolverInterface $userResolver;
    private SupportedChannelsResolverInterface $supportedChannelsResolver;
    private NotificationFactoryInterface $notificationFactory;
    private NotificationRepository $notificationRepository;
    private MessageBusInterface $messageBus;

    public function __construct(
        UserResolverInterface $userResolver, 
        SupportedChannelsResolverInterface $supportedChannelsResolver,
        NotificationFactoryInterface $notificationFactory,
        NotificationRepository $notificationRepository,
        MessageBusInterface $messageBus
    )
    {
        $this->userResolver = $userResolver;
        $this->supportedChannelsResolver = $supportedChannelsResolver;
        $this->notificationFactory = $notificationFactory;
        $this->notificationRepository = $notificationRepository;
        $this->messageBus = $messageBus;
    }

    public function __invoke(ProcessSendNotificationRequest $message)
    {
        $request = $message->getRequest();
        $user = $this->resolveUser($request->userId);

        foreach ($request->channels as $channel) {
            $notification = $this->notificationFactory->create($user, $request, $channel);

            if (!$this->supportedChannelsResolver->isChannelSupported($channel)) {
                $notification->abort(sprintf('Channel not available: %s', $channel));
                $this->notificationRepository->save($notification, true);
                continue;
            }

            $this->notificationRepository->save($notification, true);

            $this->messageBus->dispatch(new SendNotificationMessage($notification->getId()));
        }
    }

    private function resolveUser(int $id): User
    {
        $user = $this->userResolver->resolveById($id);
        if ($user === null) {
            throw new UnrecoverableMessageHandlingException(sprintf('Could not resolve user by ID %d', $id));
        }

        return $user;
    }
}
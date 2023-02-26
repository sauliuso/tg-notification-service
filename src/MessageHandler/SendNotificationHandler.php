<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Factory\ServiceProviderStrategyFactoryInterface;
use App\Message\SendNotification;
use App\Model\User;
use App\Repository\NotificationRepository;
use App\Resolver\SendNotificationRequestResolverInterface;
use App\Resolver\UserResolverInterface;
use App\ServiceProvider\AdapterException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\InvalidArgumentException;

final class SendNotificationHandler implements MessageHandlerInterface
{
    private NotificationRepository $notificationRepository;
    private ServiceProviderStrategyFactoryInterface $strategyFactory;
    private UserResolverInterface $userResolver;
    private SendNotificationRequestResolverInterface $requestDtoResolver;
    private EntityManagerInterface $entityManager;

    public function __construct(
        NotificationRepository $notificationRepository,
        ServiceProviderStrategyFactoryInterface $strategyFactory,
        UserResolverInterface $userResolver,
        SendNotificationRequestResolverInterface $requestDtoResolver,
        EntityManagerInterface $entityManager
    )
    {
        $this->notificationRepository = $notificationRepository;
        $this->strategyFactory = $strategyFactory;
        $this->userResolver = $userResolver;
        $this->requestDtoResolver = $requestDtoResolver;
        $this->entityManager = $entityManager;
    }

    public function __invoke(SendNotification $message)
    {
        $notification = $this->notificationRepository->find($message->getNotificationId());
        if ($notification === null) {
            throw new UnrecoverableMessageHandlingException(sprintf('Notification not found by ID: %d', $message->getNotificationId()));
        }

        if (!$notification->isPending() && !$notification->isFailed()) {
            // notification already processed, bail out
            return;
        }

        try {
            $user = $this->resolveUserForNotification($notification);

            try {
                $strategy = $this->strategyFactory->createForChannel($notification->getChannel());
                $strategy->setUser($user);
                $strategy->setRequestDto($this->requestDtoResolver->resolveDtoFromArray($notification->getPayload()));
                $strategy->setNotification($notification);
                $strategy->send();
            } catch (InvalidArgumentException $e) {
                $err = sprintf('Strategy for channel "%s" failed: %s', $notification->getChannel(), $e->getMessage());
                $notification->abort($err);
                throw new UnrecoverableMessageHandlingException($err, 0, $e);
            } catch (AdapterException $e) {
                // silence for messenger transport retry
            }
        } finally {
            $this->entityManager->flush();
        }
    }

    private function resolveUserForNotification(Notification $notification): User
    {
        $user = $this->userResolver->resolveById($notification->getUserId());
        if ($user === null) {
            $err = sprintf('User not found by ID: %d', $notification->getUserId());
            $notification->abort($err);
            throw new UnrecoverableMessageHandlingException($err);
        }

        return $user;
    }
}

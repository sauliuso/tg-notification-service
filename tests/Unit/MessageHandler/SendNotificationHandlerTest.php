<?php
declare(strict_types=1);

namespace AppTest\Unit\MessageHandler;

use App\Dto\SendNotificationRequest;
use App\Entity\Notification;
use App\Factory\ChannelStrategyFactoryInterface;
use App\Message\SendNotification;
use App\MessageHandler\SendNotificationHandler;
use App\Model\User;
use App\Repository\NotificationRepository;
use App\Resolver\SendNotificationRequestResolverInterface;
use App\Resolver\UserResolverInterface;
use App\ServiceProvider\ChannelStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class SendNotificationHandlerTest extends TestCase
{
    public function testStrategyGetsExecuted(): void
    {
        $notification = new Notification();
        $notification->setUserId(77);
        $notification->setChannel('email');
        $notification->setStatus(Notification::STATUS_FAILED);
        $notification->setPayload(['hello']);

        /** @var MockObject|NotificationRepository */
        $notificationsRepoMock = $this->createMock(NotificationRepository::class);
        $notificationsRepoMock
            ->method('find')
            ->with(66)
            ->willReturn($notification);

        $user = new User(77);
        $requestDto = new SendNotificationRequest();

        /** @var UserResolverInterface|MockObject */
        $userResolverMock = $this->createMock(UserResolverInterface::class);
        $userResolverMock
            ->method('resolveById')
            ->with(77)
            ->willReturn($user);

        /** @var ChannelStrategyInterface|MockObject */
        $emailStrategy = $this->createMock(ChannelStrategyInterface::class);
        $emailStrategy
            ->expects($this->once())
            ->method('send');
        $emailStrategy
            ->expects($this->once())
            ->method('setUser')
            ->with($user);
        $emailStrategy
            ->expects($this->once())
            ->method('setRequestDto')
            ->with($requestDto);
        $emailStrategy
            ->expects($this->once())
            ->method('setNotification')
            ->with($notification);

        /** @var ChannelStrategyFactoryInterface|MockObject */
        $strategyFactoryMock = $this->createMock(ChannelStrategyFactoryInterface::class);
        $strategyFactoryMock
            ->method('createForChannel')
            ->with('email')
            ->willReturn($emailStrategy);


        /** @var SendNotificationRequestResolverInterface|MockObject */
        $requestDtoResolverMock = $this->createMock(SendNotificationRequestResolverInterface::class);
        $requestDtoResolverMock
            ->method('resolveDtoFromArray')
            ->with(['hello'])
            ->willReturn($requestDto);

        /** @var EntityManagerInterface|MockObject */
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $handler = new SendNotificationHandler(
            $notificationsRepoMock,
            $strategyFactoryMock,
            $userResolverMock,
            $requestDtoResolverMock,
            $entityManagerMock
        );

        $handler(new SendNotification(66));
    }
}
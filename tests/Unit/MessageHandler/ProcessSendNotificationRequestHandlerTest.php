<?php
declare(strict_types=1);

namespace AppTest\Unit\MessageHandler;

use App\Dto\SendNotificationRequest;
use App\Entity\Notification;
use App\Factory\NotificationFactoryInterface;
use App\Message\ProcessSendNotificationRequest;
use App\Message\SendNotification;
use App\MessageHandler\ProcessSendNotificationRequestHandler;
use App\Model\User;
use App\Repository\NotificationRepository;
use App\Resolver\SupportedChannelsResolverInterface;
use App\Resolver\UserResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProcessSendNotificationRequestHandlerTest extends TestCase
{
    private UserResolverInterface|MockObject $userResolverMock;
    private SupportedChannelsResolverInterface|MockObject $supportedChannelsResolverMock;
    private NotificationFactoryInterface|MockObject $notificationFactoryMock;
    private NotificationRepository|MockObject $notificationRepoMock;
    private MessageBusInterface|MockObject $messageBusMock;
    private ProcessSendNotificationRequestHandler $handler;
    

    protected function setUp(): void
    {
        $this->userResolverMock = $this->createMock(UserResolverInterface::class);
        $this->supportedChannelsResolverMock = $this->createMock(SupportedChannelsResolverInterface::class);
        $this->notificationFactoryMock = $this->createMock(NotificationFactoryInterface::class);
        $this->notificationRepoMock = $this->createMock(NotificationRepository::class);
        $this->messageBusMock = $this->createMock(MessageBusInterface::class);
        $this->handler = new ProcessSendNotificationRequestHandler(
            $this->userResolverMock,
            $this->supportedChannelsResolverMock,
            $this->notificationFactoryMock,
            $this->notificationRepoMock,
            $this->messageBusMock
        );
    }

    public function testTerminatesMessageProcessingOnUserNotFound(): void
    {
        $this->userResolverMock
            ->expects($this->once())
            ->method('resolveById')
            ->with(66)
            ->willReturn(null);
        $this->messageBusMock
            ->expects($this->never())
            ->method('dispatch');
        $this->notificationRepoMock
            ->expects($this->never())
            ->method('save');

        $this->expectException(UnrecoverableMessageHandlingException::class);

        $request = new SendNotificationRequest();
        $request->userId = 66;
        ($this->handler)(new ProcessSendNotificationRequest($request));
    }

    public function testSendingToUnsupportedChannelCreatesAbortedNotificationEntity(): void
    {
        $user = new User(66);
        $this->userResolverMock
            ->method('resolveById')
            ->with(66)
            ->willReturn($user);

        $request = new SendNotificationRequest();
        $request->userId = 66;
        $request->channels = ['snailmail'];

        $this->supportedChannelsResolverMock
            ->expects($this->once())
            ->method('isChannelSupported')
            ->with('snailmail')
            ->willReturn(false);

        $notification = new Notification();
        $this->notificationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($user, $request, 'snailmail')
            ->willReturn($notification);

        $this->notificationRepoMock
            ->expects($this->once())
            ->method('save')
            ->with($notification);

        $this->messageBusMock
            ->expects($this->never())
            ->method('dispatch');

        ($this->handler)(new ProcessSendNotificationRequest($request));

        $this->assertEquals(Notification::STATUS_ABORTED, $notification->getStatus());
    }

    public function testSendingToSupportedChannels(): void
    {
        $user = new User(66);
        $this->userResolverMock
            ->method('resolveById')
            ->with(66)
            ->willReturn($user);

        $request = new SendNotificationRequest();
        $request->userId = 66;
        $request->channels = ['sms', 'email'];

        $this->supportedChannelsResolverMock
            ->expects($this->exactly(2))
            ->method('isChannelSupported')
            ->withConsecutive(['sms'], ['email'])
            ->willReturn(true);

        $notificationSms = new Notification();
        $notificationEmail = new Notification();
        $this->notificationFactoryMock
            ->expects($this->exactly(2))
            ->method('create')
            ->withConsecutive(
                [$user, $request, 'sms'],
                [$user, $request, 'email'],
            )
            ->willReturnOnConsecutiveCalls($notificationSms, $notificationEmail);

        $i = 1;
        $this->notificationRepoMock
            ->expects($this->exactly(2))
            ->method('save')
            ->withConsecutive([$notificationSms, true], [$notificationEmail, true])
            ->willReturnCallback(function (Notification $notification) use (&$i) {
                $reflectionClass = new \ReflectionClass($notification);
                $reflectionClass->getProperty('id')->setValue($notification, $i++);
            });

        $this->messageBusMock
            ->expects($this->exactly(2))
            ->method('dispatch')
            ->willReturnCallback(function (SendNotification $msg) {
                $this->assertTrue(in_array($msg->getNotificationId(), [1, 2]));

                return new Envelope($msg);
            });

        ($this->handler)(new ProcessSendNotificationRequest($request));

        $this->assertEquals(Notification::STATUS_PENDING, $notificationSms->getStatus());
        $this->assertEquals(Notification::STATUS_PENDING, $notificationEmail->getStatus());
    }
}
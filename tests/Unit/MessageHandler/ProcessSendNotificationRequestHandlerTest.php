<?php
declare(strict_types=1);

namespace AppTest\Unit\MessageHandler;

use App\Dto\SendNotificationRequest;
use App\Message\ProcessSendNotificationRequest;
use App\MessageHandler\ProcessSendNotificationRequestHandler;
use App\Resolver\UserResolverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

final class ProcessSendNotificationRequestHandlerTest extends TestCase
{
    private UserResolverInterface|\PHPUnit\Framework\MockObject\MockObject $userResolverMock;
    private ProcessSendNotificationRequestHandler $handler;

    protected function setUp(): void
    {
        $this->userResolverMock = $this->createMock(UserResolverInterface::class);
        $this->handler = new ProcessSendNotificationRequestHandler(
            $this->userResolverMock
        );
    }

    public function testTerminatesMessageRetryingOnUserNotFound(): void
    {
        $this->userResolverMock
            ->expects($this->once())
            ->method('resolveById')
            ->with(66)
            ->willReturn(null);

        $this->expectException(UnrecoverableMessageHandlingException::class);

        $request = new SendNotificationRequest();
        $request->userId = 66;
        ($this->handler)(new ProcessSendNotificationRequest($request));
    }
}
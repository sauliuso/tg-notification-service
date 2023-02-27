<?php
declare(strict_types=1);

namespace AppTest\Unit\Factory;

use App\Factory\ChannelStrategyFactory;
use App\Resolver\SupportedChannelsResolverInterface;
use App\ServiceProvider\AdapterInterface;
use App\ServiceProvider\ChannelStrategyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ChannelStrategyFactoryTest extends TestCase
{
    public function testCorrectStrategyGetsCreatedWithAdapters(): void
    {
        /** @var SupportedChannelsResolverInterface|MockObject */
        $supportedChannelsResolverMock = $this->createMock(SupportedChannelsResolverInterface::class);
        $supportedChannelsResolverMock
            ->method('getChannelProviders')
            ->willReturnCallback(function (string $channel) {
                $map = [
                    'email' => ['emailadapter1', 'emailadapter2', 'emailadapter4'],
                    'sms' => ['smsadapter1', 'smsadapter2'],
                ];

                return $map[$channel] ?? [];
            });

        /** @var MockObject */
        $emailStrategyMock = $this->createStrategyMock('email');
        $emailStrategyMock
            ->expects($this->exactly(2))
            ->method('addAdapter')
            ->with($this->callback(function (AdapterInterface $adapter) {
                return in_array($adapter->getProviderName(), ['emailadapter1', 'emailadapter2']);
            }));

        $factory = new ChannelStrategyFactory(
            [
                $emailStrategyMock,
                $this->createStrategyMock('sms'),
            ],
            [
                $this->createAdapterMock('emailadapter1'),
                $this->createAdapterMock('emailadapter2'),
                $this->createAdapterMock('emailadapter3'),
                $this->createAdapterMock('smsadapter1'),
            ],
            $supportedChannelsResolverMock
        );

        $strategy = $factory->createForChannel('email');
        $this->assertEquals($emailStrategyMock, $strategy);
    }

    private function createAdapterMock(string $providerName): AdapterInterface
    {
        /** @var AdapterInterface|MockObject */
        $mock = $this->createMock(AdapterInterface::class);
        $mock
            ->method('getProviderName')
            ->willReturn($providerName);

        return $mock;
    }

    private function createStrategyMock(string $channel): ChannelStrategyInterface
    {
        /** @var ChannelStrategyInterface|MockObject */
        $mock = $this->createMock(ChannelStrategyInterface::class);
        $mock
            ->method('getChannel')
            ->willReturn($channel);

        return $mock;
    }
}
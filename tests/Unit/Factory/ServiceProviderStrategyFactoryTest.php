<?php
declare(strict_types=1);

namespace AppTest\Unit\Factory;

use App\Factory\ServiceProviderStrategyFactory;
use App\Resolver\SupportedChannelsResolverInterface;
use App\ServiceProvider\ServiceProviderAdapterInterface;
use App\ServiceProvider\ServiceProviderStrategyInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ServiceProviderStrategyFactoryTest extends TestCase
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
            ->with($this->callback(function (ServiceProviderAdapterInterface $adapter) {
                return in_array($adapter->getProviderName(), ['emailadapter1', 'emailadapter2']);
            }));

        $factory = new ServiceProviderStrategyFactory(
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

    private function createAdapterMock(string $providerName): ServiceProviderAdapterInterface
    {
        /** @var ServiceProviderAdapterInterface|MockObject */
        $mock = $this->createMock(ServiceProviderAdapterInterface::class);
        $mock
            ->method('getProviderName')
            ->willReturn($providerName);

        return $mock;
    }

    private function createStrategyMock(string $channel): ServiceProviderStrategyInterface
    {
        /** @var ServiceProviderStrategyInterface|MockObject */
        $mock = $this->createMock(ServiceProviderStrategyInterface::class);
        $mock
            ->method('getChannel')
            ->willReturn($channel);

        return $mock;
    }
}
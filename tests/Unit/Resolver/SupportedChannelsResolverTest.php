<?php
declare(strict_types=1);

namespace AppTest\Unit\Resolver;

use App\Resolver\SupportedChannelsResolver;
use PHPUnit\Framework\TestCase;

final class SupportedChannelsResolverTest extends TestCase
{
    /**
     * @dataProvider dataGetSupportedChannels
     */
    public function testGetSupportedChannels(string $enabledChannels, array $channelProviders, array $expectedChannels): void
    {
        $resolver = new SupportedChannelsResolver($enabledChannels, $channelProviders);
        $this->assertEquals($expectedChannels, $resolver->getSupportedChannels());
    }

    public function dataGetSupportedChannels(): array
    {
        return [
            'no-channels-configured' => [
                '',
                ['sms' => 'smth'],
                [],
            ],
            'no-providers-configured-for-channel' => [
                'email',
                ['email' => ''],
                [],
            ],
            'no-providers-configured-for-channel-2' => [
                'email',
                ['sms' => 'smth'],
                [],
            ],
            'channel-and-providers-configured' => [
                'sms,email',
                ['sms' => 'smth'],
                ['sms'],
            ],
            'channel-and-providers-configured-2' => [
                'sms , email ',
                ['sms' => ' smth ,smth2 ', 'email' => ' a  , b '],
                ['sms','email'],
            ],
        ];
    }

    public function testIsChannelSupported(): void
    {
        $resolver = new SupportedChannelsResolver(
            'sms , email ',
            ['sms' => ' smth ,smth2 ', 'email' => '', 'push' => 'x,y'],
        );
        $this->assertTrue($resolver->isChannelSupported('sms'));
        $this->assertFalse($resolver->isChannelSupported('email'));
        $this->assertFalse($resolver->isChannelSupported('push'));
    }

    public function testGetChannelProviders(): void
    {
        $resolver = new SupportedChannelsResolver(
            'sms , email ',
            ['sms' => ' smth ,smth2 ', 'email' => '', 'push' => 'x,y'],
        );
        $this->assertEquals(['smth', 'smth2'], $resolver->getChannelProviders('sms'));
        $this->assertEquals([], $resolver->getChannelProviders('email'));
        $this->assertEquals([], $resolver->getChannelProviders('push'));
        $this->assertEquals([], $resolver->getChannelProviders('nonexistant'));
    }
}
<?php

namespace App\Resolver;

interface SupportedChannelsResolverInterface
{
    /**
     * @return string[]
     */
    public function getSupportedChannels(): array;
    
    public function isChannelSupported(string $channel): bool;

    /**
     * @return string[]
     */
    public function getChannelProviders(string $channel): array;
}
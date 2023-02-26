<?php
declare(strict_types=1);

namespace App\Resolver;

use App\Util\StringConverter;

final class SupportedChannelsResolver implements SupportedChannelsResolverInterface
{
    private array $channelProviders = [];

    public function __construct(string $enabledChannelsList, array $channelProviders)
    {
        $enabledChannels = StringConverter::envStringToArray($enabledChannelsList);

        foreach ($channelProviders as $channel => $providers) {
            $providers = StringConverter::envStringToArray($providers);

            // channel is supported if it is enabled and has at least one provider set
            if (in_array($channel, $enabledChannels) && !empty($providers)) {
                $this->channelProviders[$channel] = $providers;
            }
        }
    }

    public function getSupportedChannels(): array
    {
        return array_keys($this->channelProviders);
    }

    public function isChannelSupported(string $channel): bool
    {
        return in_array($channel, $this->getSupportedChannels());
    }

    public function getChannelProviders(string $channel): array
    {
        return $this->channelProviders[$channel] ?? [];
    }
}
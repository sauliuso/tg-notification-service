<?php
declare(strict_types=1);

namespace App\Factory;

use App\Resolver\SupportedChannelsResolverInterface;
use App\ServiceProvider\AdapterInterface;
use App\ServiceProvider\ChannelStrategyInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final class ChannelStrategyFactory implements ChannelStrategyFactoryInterface
{
    /** @var ChannelStrategyInterface[] */
    private iterable $strategies;
    /** @var AdapterInterface[] */
    private iterable $adapters;
    private SupportedChannelsResolverInterface $supportedChannelsResolver;

    public function __construct(
        #[TaggedIterator('app.service_provider.strategy')] iterable $strategies,
        #[TaggedIterator('app.service_provider.adapter')] iterable $adapters,
        SupportedChannelsResolverInterface $supportedChannelsResolver
    )
    {
        $this->strategies = $strategies;
        $this->adapters = $adapters;
        $this->supportedChannelsResolver = $supportedChannelsResolver;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function createForChannel(string $channel): ChannelStrategyInterface
    {
        $strategy = $this->resolveStrategy($channel);

        $adapters = $this->resolveAdapters($channel);
        foreach ($adapters as $adapter) {
            $strategy->addAdapter($adapter);
        }

        return $strategy;
    }

    private function resolveStrategy(string $channel): ChannelStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getChannel() === $channel) {
                return $strategy;
            }
        }

        throw new InvalidArgumentException(sprintf('Strategy for channel "%s" is not available', $channel));
    }

    private function resolveAdapters(string $channel): iterable
    {
        $adapters = [];

        foreach ($this->supportedChannelsResolver->getChannelProviders($channel) as $providerName) {
            reset($this->adapters);
            foreach ($this->adapters as $adapter) {
                if ($adapter->getProviderName() === $providerName) {
                    $adapters[] = $adapter;
                    break;
                }
            }
        }
        Assert::notEmpty($adapters, sprintf('No service providers enabled for channel "%s"', $channel));

        return $adapters;
    }
}
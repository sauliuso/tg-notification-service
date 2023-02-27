<?php
declare(strict_types=1);

namespace App\Factory;

use App\ServiceProvider\ChannelStrategyInterface;

interface ChannelStrategyFactoryInterface
{
    public function createForChannel(string $channel): ChannelStrategyInterface;
}
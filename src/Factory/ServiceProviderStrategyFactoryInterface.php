<?php
declare(strict_types=1);

namespace App\Factory;

use App\ServiceProvider\ServiceProviderStrategyInterface;

interface ServiceProviderStrategyFactoryInterface
{
    public function createForChannel(string $channel): ServiceProviderStrategyInterface;
}
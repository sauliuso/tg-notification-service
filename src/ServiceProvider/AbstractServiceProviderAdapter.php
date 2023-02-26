<?php
declare(strict_types=1);

namespace App\ServiceProvider;

abstract class AbstractServiceProviderAdapter implements ServiceProviderAdapterInterface
{
    abstract public function send(): array;

    public function initialize(): void
    {

    }
}
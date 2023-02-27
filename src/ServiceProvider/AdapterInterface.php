<?php
declare(strict_types=1);

namespace App\ServiceProvider;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.service_provider.adapter')]
interface AdapterInterface
{
    public function initialize(): void;

    /**
     * @throws AdapterException
     */
    public function send(): array;

    public function getProviderName(): string;
}
<?php
declare(strict_types=1);

namespace App\ServiceProvider\Adapter;

use App\ServiceProvider\AbstractSmsAdapter;
use App\ServiceProvider\AdapterException;

final class MockFailedSmsAdapter extends AbstractSmsAdapter
{
    public function getProviderName(): string
    {
        return 'mockfailedsms';
    }

    public function send(): array
    {
        dump('"mockfailedsms" executed, about to fail!');

        throw new AdapterException('Oh no! This service provider failed!');
    }
}
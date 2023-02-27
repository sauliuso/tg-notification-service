<?php
declare(strict_types=1);

namespace App\ServiceProvider\Adapter;

final class MockSmsAdapter extends AbstractSmsAdapter
{
    public function getProviderName(): string
    {
        return 'mocksms';
    }

    public function send(): array
    {
        dump('Mock sending SMS', [
            'from' => $this->getFromNumber(),
            'to' => $this->getToNumber(),
            'text' => $this->getBody(),
        ]);

        return ['result' => 'ok'];
    }
}
<?php
declare(strict_types=1);

namespace App\ServiceProvider\Adapter;
use App\ServiceProvider\AbstractEmailAdapter;

final class MockEmailAdapter extends AbstractEmailAdapter
{
    public function getProviderName(): string
    {
        return 'mockemail';
    }

    public function send(): array
    {
        dump('Mock sending email', [
            'from' => $this->getFromEmail(),
            'to' => $this->getToEmail(),
            'subj' => $this->getSubj(),
            'text' => $this->getBody(),
        ]);

        return ['result' => 'ok'];
    }
}
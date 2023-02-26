<?php
declare(strict_types=1);

namespace App\ServiceProvider;

use App\ServiceProvider\AbstractServiceProviderAdapter;
use App\ServiceProvider\SmsServiceAdapterInterface;

abstract class AbstractSmsAdapter extends AbstractServiceProviderAdapter implements SmsServiceAdapterInterface
{
    protected string $fromNumber;
    protected string $toNumber;
    protected string $body;

    public function setFromNumber(string $number): void
    {
        $this->fromNumber = $number;
    }

    public function setToNumber(string $number): void
    {
        $this->toNumber = $number;
    }

    public function setBody(string $text): void
    {
        $this->body = $text;
    }

    public function getFromNumber(): string
    {
        return $this->fromNumber;
    }

    public function getToNumber(): string
    {
        return $this->toNumber;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
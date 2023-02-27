<?php
declare(strict_types=1);

namespace App\ServiceProvider\Adapter;

abstract class AbstractSmsAdapter extends AbstractAdapter implements SmsAdapterInterface
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

    protected function getFromNumber(): string
    {
        return $this->fromNumber;
    }

    protected function getToNumber(): string
    {
        return $this->toNumber;
    }

    protected function getBody(): string
    {
        return $this->body;
    }
}
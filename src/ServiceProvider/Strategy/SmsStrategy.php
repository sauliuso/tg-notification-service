<?php
declare(strict_types=1);

namespace App\ServiceProvider\Strategy;

use App\ServiceProvider\AdapterInterface;
use App\ServiceProvider\Adapter\SmsAdapterInterface;
use Webmozart\Assert\Assert;

final class SmsStrategy extends AbstractChannelStrategy
{
    private string $fromNumber;

    public function __construct(string $fromNumber)
    {
        $this->fromNumber = $fromNumber;
    }

    public function getChannel(): string
    {
        return 'sms';
    }

    protected function initializeAdapter(AdapterInterface $adapter): void
    {
        /** @var SmsAdapterInterface $adapter */
        Assert::isInstanceOf($adapter, SmsAdapterInterface::class);

        $adapter->setFromNumber($this->fromNumber);
        $adapter->setToNumber($this->user->getPhoneNumber());
        $adapter->setBody($this->requestDto->body);
        $adapter->initialize();
    }
}
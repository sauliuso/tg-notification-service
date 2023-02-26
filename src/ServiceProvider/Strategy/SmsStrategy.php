<?php
declare(strict_types=1);

namespace App\ServiceProvider\Strategy;

use App\ServiceProvider\AbstractServiceProviderStrategy;
use App\ServiceProvider\ServiceProviderAdapterInterface;
use App\ServiceProvider\SmsServiceAdapterInterface;
use Webmozart\Assert\Assert;

final class SmsStrategy extends AbstractServiceProviderStrategy
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

    protected function initializeAdapter(ServiceProviderAdapterInterface $adapter): void
    {
        /** @var SmsServiceAdapterInterface $adapter */
        Assert::isInstanceOf($adapter, SmsServiceAdapterInterface::class);

        $adapter->setFromNumber($this->fromNumber);
        $adapter->setToNumber($this->user->getPhoneNumber());
        $adapter->setBody($this->requestDto->body);
        $adapter->initialize();
    }
}
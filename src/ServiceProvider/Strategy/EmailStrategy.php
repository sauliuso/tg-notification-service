<?php
declare(strict_types=1);

namespace App\ServiceProvider\Strategy;

use App\ServiceProvider\Adapter\EmailAdapterInterface;
use App\ServiceProvider\AdapterInterface;
use App\ServiceProvider\Strategy\AbstractChannelStrategy;
use Webmozart\Assert\Assert;

final class EmailStrategy extends AbstractChannelStrategy
{
    private string $fromName;
    private string $fromEmail;
    private string $defaultSubj;

    public function __construct(string $fromName, string $fromEmail, string $defaultSubj)
    {
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->defaultSubj = $defaultSubj;
    }

    public function getChannel(): string
    {
        return 'email';
    }

    protected function initializeAdapter(AdapterInterface $adapter): void
    {
        /** @var EmailAdapterInterface $adapter */
        Assert::isInstanceOf($adapter, EmailAdapterInterface::class);

        $adapter->setFromName($this->fromName);
        $adapter->setFromEmail($this->fromEmail);
        $adapter->setToEmail($this->user->getEmail());
        $adapter->setBody($this->requestDto->body);
        $adapter->setSubj($this->requestDto->title ?? $this->defaultSubj);
        $adapter->initialize();
    }
}
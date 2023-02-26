<?php
declare(strict_types=1);

namespace App\ServiceProvider\Strategy;

use App\ServiceProvider\AbstractServiceProviderStrategy;
use App\ServiceProvider\EmailServiceAdapterInterface;
use App\ServiceProvider\ServiceProviderAdapterInterface;
use Webmozart\Assert\Assert;

final class EmailStrategy extends AbstractServiceProviderStrategy
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

    protected function initializeAdapter(ServiceProviderAdapterInterface $adapter): void
    {
        /** @var EmailServiceAdapterInterface $adapter */
        Assert::isInstanceOf($adapter, EmailServiceAdapterInterface::class);

        $adapter->setFromName($this->fromName);
        $adapter->setFromEmail($this->fromEmail);
        $adapter->setToEmail($this->user->getEmail());
        $adapter->setBody($this->requestDto->body);
        $adapter->setSubj($this->requestDto->title ?? $this->defaultSubj);
        $adapter->initialize();
    }
}
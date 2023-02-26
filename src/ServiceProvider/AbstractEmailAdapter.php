<?php
declare(strict_types=1);

namespace App\ServiceProvider;

use App\ServiceProvider\AbstractServiceProviderAdapter;
use App\ServiceProvider\EmailServiceAdapterInterface;

abstract class AbstractEmailAdapter extends AbstractServiceProviderAdapter implements EmailServiceAdapterInterface
{
    protected string $fromName;
    protected string $fromEmail;
    protected string $toEmail;
    protected string $body;
    protected string $subj;

    public function setFromName(string $name): void
    {
        $this->fromName = $name;
    }

    public function setFromEmail(string $email): void
    {
        $this->fromEmail = $email;
    }

    public function setToEmail(string $email): void
    {
        $this->toEmail = $email;
    }

    public function setBody(string $text): void
    {
        $this->body = $text;
    }

    public function setSubj(string $subj): void
    {
        $this->subj = $subj;
    }

    public function getFromName(): string
    {
        return $this->fromName;
    }

    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    public function getToEmail(): string
    {
        return $this->toEmail;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getSubj(): string
    {
        return $this->subj;
    }
}
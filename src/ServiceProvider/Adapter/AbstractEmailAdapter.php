<?php
declare(strict_types=1);

namespace App\ServiceProvider\Adapter;

abstract class AbstractEmailAdapter extends AbstractAdapter implements EmailAdapterInterface
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

    protected function getFromName(): string
    {
        return $this->fromName;
    }

    protected function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    protected function getToEmail(): string
    {
        return $this->toEmail;
    }

    protected function getBody(): string
    {
        return $this->body;
    }

    protected function getSubj(): string
    {
        return $this->subj;
    }
}
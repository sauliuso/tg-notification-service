<?php

namespace App\ServiceProvider;

interface EmailServiceAdapterInterface extends ServiceProviderAdapterInterface
{
    public function setFromName(string $name): void;

    public function setFromEmail(string $email): void;

    public function setToEmail(string $email): void;

    public function setSubj(string $subj): void;

    public function setBody(string $body): void;

    public function getFromName(): string;

    public function getFromEmail(): string;

    public function getToEmail(): string;

    public function getSubj(): string;

    public function getBody(): string;
}
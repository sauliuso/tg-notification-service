<?php

namespace App\ServiceProvider\Adapter;

use App\ServiceProvider\AdapterInterface;

interface EmailAdapterInterface extends AdapterInterface
{
    public function setFromName(string $name): void;

    public function setFromEmail(string $email): void;

    public function setToEmail(string $email): void;

    public function setSubj(string $subj): void;

    public function setBody(string $body): void;
}
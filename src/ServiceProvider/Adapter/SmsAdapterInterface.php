<?php

namespace App\ServiceProvider\Adapter;

use App\ServiceProvider\AdapterInterface;

interface SmsAdapterInterface extends AdapterInterface
{
    public function setFromNumber(string $number): void;

    public function setToNumber(string $number): void;

    public function setBody(string $text): void;
}
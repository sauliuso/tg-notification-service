<?php

namespace App\ServiceProvider;

interface SmsServiceAdapterInterface extends ServiceProviderAdapterInterface
{
    public function setFromNumber(string $number): void;

    public function setToNumber(string $number): void;

    public function setBody(string $text): void;

    public function getFromNumber(): string;

    public function getToNumber(): string;

    public function getBody(): string;
}
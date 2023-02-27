<?php
declare(strict_types=1);

namespace App\ServiceProvider\Adapter;

use App\ServiceProvider\AdapterInterface;

abstract class AbstractAdapter implements AdapterInterface
{
    abstract public function send(): array;

    public function initialize(): void
    {

    }
}
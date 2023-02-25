<?php

namespace App\Resolver;

use App\Model\User;

interface UserResolverInterface
{
    public function resolveById(int $id): ?User;
}
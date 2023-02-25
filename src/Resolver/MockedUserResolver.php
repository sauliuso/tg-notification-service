<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Model\User;

final class MockedUserResolver implements UserResolverInterface
{
    private string $email;
    private string $phoneNumber;

    public function __construct(string $email, string $phoneNumber)
    {
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    public function resolveById(int $id): ?User
    {
        switch ($id) {
            case 1:
                return new User(1, $this->email, $this->phoneNumber);
            case 2:
                return new User(2, $this->email, null);
            case 3:
                return new User(3, null, $this->phoneNumber);
            case 4:
                return new User(4, null, null);
            case 5:
                throw new \Exception('Something went wrong while resolving user!'); 
            default:
                return null;
        }
    }
}
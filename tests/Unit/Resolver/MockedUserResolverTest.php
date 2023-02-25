<?php

declare(strict_types=1);

namespace AppTest\Unit\Resolver;

use App\Resolver\MockedUserResolver;
use Exception;
use PHPUnit\Framework\TestCase;

final class MockedUserResolverTest extends TestCase
{
    public function testResolveByIdKnown(): void
    {
        $resolver = new MockedUserResolver('email@example.com', '+1234567890');
        $user = $resolver->resolveById(1);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('email@example.com', $user->getEmail());
        $this->assertEquals('+1234567890', $user->getPhoneNumber());
    }

    public function testResolveByIdUnknown(): void
    {
        $resolver = new MockedUserResolver('email@example.com', '+1234567890');
        $this->assertNull($resolver->resolveById(666));
    }

    public function testResolveByIdException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Something went wrong while resolving user!');

        $resolver = new MockedUserResolver('a', 'b');
        $resolver->resolveById(5);
    }
}
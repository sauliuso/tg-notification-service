<?php
declare(strict_types=1);

namespace AppTest\Unit\Util;

use App\Util\StringConverter;
use PHPUnit\Framework\TestCase;

final class StringConverterTest extends TestCase
{
    public function testEnvStringToArray(): void
    {
        $this->assertEquals([], StringConverter::envStringToArray(''));
        $this->assertEquals([], StringConverter::envStringToArray('   '));
        $this->assertEquals(['a'], StringConverter::envStringToArray('a'));
        $this->assertEquals(['a'], StringConverter::envStringToArray(' a '));
        $this->assertEquals(['a', 'b', 'c'], StringConverter::envStringToArray('a ,  b,c  '));
    }
}
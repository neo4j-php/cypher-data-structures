<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\OptionName;

class OptionNameTest extends TestCase
{
    public function validOptionNameProvider(): array
    {
        return [
            ['valid'],
            ['validOptionName'],
            ['_valid'],
            ['validOption123Name'],
            ['spatial.wgs-84.min'],
            ['something._-special'],
        ];
    }

    /**
     * @dataProvider validOptionNameProvider
     */
    public function testValidOptionName(string $optionName): void
    {
        $option = new OptionName($optionName);
        $this->assertSame($optionName, $option->getOptionName());
        $this->assertSame($optionName, (string) $option);
    }

    public function invalidOptionNameProvider(): array
    {
        return [
            ['this is invalid'],
            ['+invalid'],
        ];
    }

    /**
     * @dataProvider invalidOptionNameProvider
     */
    public function testInvalidOptionName(string $optionName): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage(sprintf(
            "Expected string '%s' to follow regex for strings containing only a-z, A-Z, 0-9 and the characters \".\", \"_\" and \"-\", '/^[a-zA-Z0-9._-]*$/'",
            $optionName
        ));
        $this->expectException(InvalidArgumentException::class);
        new OptionName($optionName);
    }

    public function testIsEqualTo(): void
    {
        $optionNameA = new OptionName('someOption');
        $optionNameB = new OptionName('someOption');
        $optionNameC = new OptionName('otherOption');
        $this->assertTrue($optionNameA->isEqualTo($optionNameB));
        $this->assertTrue($optionNameB->isEqualTo($optionNameA));
        $this->assertFalse($optionNameA->isEqualTo($optionNameC));
        $this->assertFalse($optionNameC->isEqualTo($optionNameA));
        $this->assertFalse($optionNameA->isEqualTo('something else'));
    }
}

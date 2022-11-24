<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToStringHelper;

class ToStringHelperTest extends TestCase
{
    public function testMustNameBeEscaped(): void
    {
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('abc'));
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('Abc'));
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('ABC'));
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('abc123'));
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('abc_123'));
        $this->assertTrue(ToStringHelper::mustNameBeEscaped('123'));
        $this->assertTrue(ToStringHelper::mustNameBeEscaped('abc.abc'));
        $this->assertTrue(ToStringHelper::mustNameBeEscaped('abc abc'));
    }

    public function escapeStringProvider(): array
    {
        return [
            ['hello world', 'hello world'],
            ["hello ' world", "hello \' world"],                    // hello ' world
            ["hello \' world", "hello \' world"],                   // hello \' world
            ["hello \\' world", "hello \' world"],                  // hello \' world
            ["hello \\\' world", "hello \\\\\' world"],             // hello \\' world
            ["hello \\\\' world", "hello \\\\\' world"],            // hello \\' world
            ["hello \\\\\' world", "hello \\\\\' world"],           // hello \\\' world
            ["hello \\\\\\' world", "hello \\\\\' world"],          // hello \\\' world
            ["hello \\\\\\\' world", "hello \\\\\\\\\' world"],     // hello \\\\' world
            ["hello \\\\\\\\' world", "hello \\\\\\\\\' world"],    // hello \\\\' world
        ];
    }

    /**
     * @dataProvider escapeStringProvider
     */
    public function testEscapeCharacter(string $string, string $output): void
    {
        $result = ToStringHelper::escapeString($string);
        $this->assertSame($output, $result);
    }

    public function testInvalidEscapeCharacter(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage('Escape character must be of length 1, got \'--\'');
        $this->expectException(InvalidArgumentException::class);
        ToStringHelper::escapeString('some string', '--');
    }

    public function valueToStringProvider(): array
    {
        return [
            [null, 'null'],
            [true, 'true'],
            [false, 'false'],
            [0, '0'],
            [123, '123'],
            [1.23, '1.23'],
            ['some string', "'some string'"],
            ['some \'string', "'some \'string'"],
            [[1, 2, 3], '[1, 2, 3]'],
            [[1, 3, 2], '[1, 2, 3]'],
            [[0, null, 'hi', 'abc'], "[0, null, 'abc', 'hi']"],
        ];
    }

    /**
     * @dataProvider valueToStringProvider
     */
    public function testValueToString($value, $string): void
    {
        $this->assertSame($string, ToStringHelper::valueToString($value));
    }

    public function testPropertyArrayToString(): void
    {
        $properties = [
            'int' => 123,
            'float' => 123.4,
            'string' => 'string',
            'stringWithSpace' => 'hello world',
            'stringWithDot' => 'hello.world',
            'stringWithBacktick' => 'hello\'world',
            'array' => ['a', 'b', 'c'],
            'problematic .\' name' => 'hi :D',
        ];
        $this->assertSame("array: ['a', 'b', 'c'], float: 123.4, int: 123, `problematic .\' name`: 'hi :D', string: 'string', stringWithBacktick: 'hello\'world', stringWithDot: 'hello.world', stringWithSpace: 'hello world'", ToStringHelper::propertyArrayToString($properties));
    }

    public function testLabelsToString(): void
    {
        $labels = ['a', 'z', 'b', 'E', '_c', '012', 'problematic label', '#label'];
        $this->assertSame(":`#label`:`012`:E:_c:a:b:`problematic label`:z", ToStringHelper::labelsToString($labels));
    }
}

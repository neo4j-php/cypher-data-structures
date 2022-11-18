<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\EscapeHelper;

class EscapeHelperTest extends TestCase
{
    public function escapeCharacterProvider(): array
    {
        return [
            ["'", 'hello world', 'hello world'],
            ["'", "hello ' world", "hello \' world"],                    // hello ' world
            ["'", "hello \' world", "hello \' world"],                   // hello \' world
            ["'", "hello \\' world", "hello \' world"],                  // hello \' world
            ["'", "hello \\\' world", "hello \\\\\' world"],             // hello \\' world
            ["'", "hello \\\\' world", "hello \\\\\' world"],            // hello \\' world
            ["'", "hello \\\\\' world", "hello \\\\\' world"],           // hello \\\' world
            ["'", "hello \\\\\\' world", "hello \\\\\' world"],          // hello \\\' world
            ["'", "hello \\\\\\\' world", "hello \\\\\\\\\' world"],     // hello \\\\' world
            ["'", "hello \\\\\\\\' world", "hello \\\\\\\\\' world"],    // hello \\\\' world
        ];
    }

    /**
     * @dataProvider escapeCharacterProvider
     */
    public function testEscapeCharacter(string $escapeCharacter, string $input, string $output): void
    {
        $result = EscapeHelper::escapeCharacter($escapeCharacter, $input);
        $this->assertSame($output, $result);
    }

    public function testInvalidEscapeCharacter(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage('Escape character must be of length 1, got \'--\'');
        $this->expectException(InvalidArgumentException::class);
        EscapeHelper::escapeCharacter('--', 'some input');
    }
}

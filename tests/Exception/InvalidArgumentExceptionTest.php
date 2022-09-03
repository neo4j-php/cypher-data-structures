<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Exception;

use DateTime;
use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Exception\CypherDataStructureException;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class InvalidArgumentExceptionTest extends TestCase
{
    public function testCreateForTypeMismatch(): void
    {
        $exception = InvalidArgumentException::createForTypeMismatch(DateTime::class, stdClass::class);
        $this->assertSame("Expected type 'DateTime', got type 'stdClass'", $exception->getMessage());
        $this->assertInstanceOf(CypherDataStructureException::class, $exception);
    }

    public function testCreateForRegexMismatch(): void
    {
        $exception = InvalidArgumentException::createForRegexMismatch('a', 'b', 'c');
        $this->assertSame("Expected string 'c' to follow regex for b, 'a'", $exception->getMessage());
        $this->assertInstanceOf(CypherDataStructureException::class, $exception);
    }
}

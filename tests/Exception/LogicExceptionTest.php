<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\CypherDataStructureException;
use Syndesi\CypherDataStructures\Exception\LogicException;

class LogicExceptionTest extends TestCase
{
    public function testCreateForInternalTypeMismatch(): void
    {
        $exception = LogicException::createForInternalTypeMismatch(\DateTime::class, \stdClass::class);
        $this->assertSame("Internal type mismatch, expected type 'DateTime', got type 'stdClass'", $exception->getMessage());
        $this->assertInstanceOf(CypherDataStructureException::class, $exception);
    }
}

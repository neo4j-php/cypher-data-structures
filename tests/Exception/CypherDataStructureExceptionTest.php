<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Exception;

use Exception;
use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\CypherDataStructureException;

class CypherDataStructureExceptionTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $exception = new CypherDataStructureException('some message');
        $this->assertInstanceOf(Exception::class, $exception);
    }
}

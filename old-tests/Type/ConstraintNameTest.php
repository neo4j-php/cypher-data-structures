<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\ConstraintName;

class ConstraintNameTest extends TestCase
{
    public function validConstraintNameProvider(): array
    {
        return [
            ['valid'],
            ['valid_constraint_name'],
            ['_valid'],
            ['valid_constraint123_name'],
        ];
    }

    /**
     * @dataProvider validConstraintNameProvider
     */
    public function testValidConstraintName(string $constraintName): void
    {
        $property = new ConstraintName($constraintName);
        $this->assertSame($constraintName, $property->getConstraintName());
        $this->assertSame($constraintName, (string) $property);
    }

    public function invalidConstraintNameProvider(): array
    {
        return [
            ['Invalid'],
            ['invalidConstraintName'],
            ['invalid Constraint Name'],
            ['123name'],
        ];
    }

    /**
     * @dataProvider invalidConstraintNameProvider
     */
    public function testInvalidConstraintName(string $constraintName): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage(sprintf(
            "Expected string '%s' to follow regex for snake_case with optional underscore (_) at beginning, '/^_?([a-z][a-z0-9]*)((\d)|(_[a-z0-9]+))*([a-z])?$/'",
            $constraintName
        ));
        $this->expectException(InvalidArgumentException::class);
        new ConstraintName($constraintName);
    }

    public function testIsEqualTo(): void
    {
        $constraintNameA = new ConstraintName('some_constraint_name');
        $constraintNameB = new ConstraintName('some_constraint_name');
        $constraintNameC = new ConstraintName('other_constraint_name');
        $this->assertTrue($constraintNameA->isEqualTo($constraintNameB));
        $this->assertTrue($constraintNameB->isEqualTo($constraintNameA));
        $this->assertFalse($constraintNameA->isEqualTo($constraintNameC));
        $this->assertFalse($constraintNameC->isEqualTo($constraintNameA));
        $this->assertFalse($constraintNameA->isEqualTo('something else'));
    }
}

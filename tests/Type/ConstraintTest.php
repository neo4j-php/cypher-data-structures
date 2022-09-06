<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\Constraint;
use Syndesi\CypherDataStructures\Type\ConstraintName;
use Syndesi\CypherDataStructures\Type\ConstraintType;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\RelationType;

class ConstraintTest extends TestCase
{
    public function testConstraintName(): void
    {
        $constraint = new Constraint();
        $this->assertNull($constraint->getConstraintName());
        $constraint->setConstraintName(new ConstraintName('some_name'));
        $this->assertSame('some_name', $constraint->getConstraintName()->getConstraintName());
        $constraint->setConstraintName(null);
        $this->assertNull($constraint->getConstraintName());
    }

    public function testConstraintType(): void
    {
        $constraint = new Constraint();
        $this->assertNull($constraint->getConstraintType());
        $constraint->setConstraintType(ConstraintType::UNIQUE);
        $this->assertSame(ConstraintType::UNIQUE, $constraint->getConstraintType());
        $constraint->setConstraintType(null);
        $this->assertNull($constraint->getConstraintType());
    }

    public function testFor(): void
    {
        $constraint = new Constraint();
        $this->assertNull($constraint->getFor());
        $constraint->setFor(new NodeLabel('SomeNode'));
        $this->assertSame('SomeNode', (string) $constraint->getFor());
        $constraint->setFor(new RelationType('SOME_TYPE'));
        $this->assertSame('SOME_TYPE', (string) $constraint->getFor());
        $constraint->setFor(null);
        $this->assertNull($constraint->getFor());
    }

    public function testOptions(): void
    {
        $constraint = new Constraint();
        $this->assertCount(0, $constraint->getOptions());
        $constraint->setOptions(['some' => 'options']);
        $this->assertCount(1, $constraint->getOptions());
        $this->assertArrayHasKey('some', $constraint->getOptions());
        $constraint->setOptions([]);
        $this->assertCount(0, $constraint->getOptions());
    }
}

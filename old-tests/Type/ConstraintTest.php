<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Type\Constraint;
use Syndesi\CypherDataStructures\Type\ConstraintName;
use Syndesi\CypherDataStructures\Type\ConstraintType;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\PropertyName;
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

    public function testToString(): void
    {
        $constraint = new Constraint();
        $constraint->setConstraintName(new ConstraintName('constraint'));
        $constraint->setFor(new NodeLabel('Node'));
        $constraint->setConstraintType(ConstraintType::UNIQUE);
        $constraint->addProperty(new PropertyName('id'));
        $this->assertSame('CONSTRAINT constraint FOR (element:Node) REQUIRE (element.id) IS UNIQUE', (string) $constraint);
    }

    public function testIsEqualTo(): void
    {
        $constraintA = new Constraint();
        $constraintA->setConstraintName(new ConstraintName('constraint_a'));
        $constraintA->setFor(new NodeLabel('NodeA'));
        $constraintA->addProperty(new PropertyName('id'));
        $constraintA->setConstraintType(ConstraintType::UNIQUE);

        $constraintB = new Constraint();
        $constraintB->setConstraintName(new ConstraintName('constraint_a'));
        $constraintB->setFor(new NodeLabel('NodeA'));
        $constraintB->addProperty(new PropertyName('id'));
        $constraintB->setConstraintType(ConstraintType::UNIQUE);

        $constraintC = new Constraint();
        $constraintC->setConstraintName(new ConstraintName('constraint_a'));
        $constraintC->setFor(new RelationType('RELATION_A'));
        $constraintC->addProperty(new PropertyName('id'));
        $constraintC->setConstraintType(ConstraintType::UNIQUE);

        $this->assertTrue($constraintA->isEqualTo($constraintB));
        $this->assertTrue($constraintB->isEqualTo($constraintA));
        $this->assertFalse($constraintA->isEqualTo($constraintC));
        $this->assertFalse($constraintC->isEqualTo($constraintA));
        $this->assertFalse($constraintA->isEqualTo(new stdClass()));
        $this->assertFalse($constraintA->isEqualTo('something'));
    }
}

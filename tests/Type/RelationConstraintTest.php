<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\RelationConstraint;

class RelationConstraintTest extends TestCase
{
    public function testIndexIsInitiallyEmpty(): void
    {
        $relationConstraint = new RelationConstraint();
        $this->assertEmpty($relationConstraint->getName());
        $this->assertEmpty($relationConstraint->getType());
        $this->assertEmpty($relationConstraint->getFor());
    }

    public function testName(): void
    {
        $relationConstraint = new RelationConstraint();
        $this->assertNull($relationConstraint->getName());
        $relationConstraint->setName('some_name');
        $this->assertSame('some_name', $relationConstraint->getName());
        $relationConstraint->setName(null);
        $this->assertNull($relationConstraint->getName());
    }

    public function testType(): void
    {
        $relationConstraint = new RelationConstraint();
        $this->assertNull($relationConstraint->getType());
        $relationConstraint->setType('SOME_TYPE');
        $this->assertSame('SOME_TYPE', $relationConstraint->getType());
        $relationConstraint->setType(null);
        $this->assertNull($relationConstraint->getType());
    }

    public function testFor(): void
    {
        $relationConstraint = new RelationConstraint();
        $this->assertNull($relationConstraint->getFor());
        $relationConstraint->setFor('RELATION');
        $this->assertSame('RELATION', $relationConstraint->getFor());
        $relationConstraint->setFor(null);
        $this->assertNull($relationConstraint->getFor());
    }

    public function testToString(): void
    {
        $relationConstraint = (new RelationConstraint())
            ->setName('name')
            ->setType('UNIQUE')
            ->setFor('RELATION')
            ->addProperty('id', 'not null');
        $this->assertSame("CONSTRAINT name FOR ()-[relation:RELATION]-() REQUIRE relation.id IS UNIQUE", (string) $relationConstraint);
    }

    public function testIsEqualTo(): void
    {
        $relationConstraint = (new RelationConstraint())
            ->setName('name')
            ->setFor('RELATION')
            ->setType('UNIQUE')
            ->addProperty('id', 'not null');
        $otherRelationConstraint = (clone $relationConstraint)
            ->setName('other_index');
        $this->assertFalse($relationConstraint->isEqualTo(123));
        $this->assertFalse($relationConstraint->isEqualTo($otherRelationConstraint));
        $this->assertTrue($relationConstraint->isEqualTo($relationConstraint));
        $this->assertTrue($relationConstraint->isEqualTo(clone $relationConstraint));
    }
}

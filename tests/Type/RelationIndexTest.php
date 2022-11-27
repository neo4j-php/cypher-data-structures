<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\RelationIndex;

class RelationIndexTest extends TestCase
{
    public function testIndexIsInitiallyEmpty(): void
    {
        $relationIndex = new RelationIndex();
        $this->assertEmpty($relationIndex->getName());
        $this->assertEmpty($relationIndex->getType());
        $this->assertEmpty($relationIndex->getFor());
    }

    public function testName(): void
    {
        $relationIndex = new RelationIndex();
        $this->assertNull($relationIndex->getName());
        $relationIndex->setName('some_name');
        $this->assertSame('some_name', $relationIndex->getName());
        $relationIndex->setName(null);
        $this->assertNull($relationIndex->getName());
    }

    public function testType(): void
    {
        $relationIndex = new RelationIndex();
        $this->assertNull($relationIndex->getType());
        $relationIndex->setType('BTREE');
        $this->assertSame('BTREE', $relationIndex->getType());
        $relationIndex->setType(null);
        $this->assertNull($relationIndex->getType());
    }

    public function testFor(): void
    {
        $relationIndex = new RelationIndex();
        $this->assertNull($relationIndex->getFor());
        $relationIndex->setFor('SOME_RELATION_TYPE');
        $this->assertSame('SOME_RELATION_TYPE', $relationIndex->getFor());
        $relationIndex->setFor(null);
        $this->assertNull($relationIndex->getFor());
    }

    public function testToString(): void
    {
        $relationIndex = (new RelationIndex())
            ->setName('name')
            ->setType('BTREE')
            ->setFor('RELATION')
            ->addProperty('id', 'not null');
        $this->assertSame("BTREE INDEX name FOR ()-[relation:RELATION]-() ON (relation.id)", (string) $relationIndex);
    }

    public function testIsEqualTo(): void
    {
        $relationIndex = (new RelationIndex())
            ->setName('name')
            ->setFor('RELATION')
            ->setType('BTREE')
            ->addProperty('id', 'not null');
        $otherRelationIndex = (clone $relationIndex)
            ->setName('other_index');
        $this->assertFalse($relationIndex->isEqualTo(123));
        $this->assertFalse($relationIndex->isEqualTo($otherRelationIndex));
        $this->assertTrue($relationIndex->isEqualTo($relationIndex));
        $this->assertTrue($relationIndex->isEqualTo(clone $relationIndex));
    }
}

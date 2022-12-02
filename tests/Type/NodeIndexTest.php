<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\NodeIndex;

class NodeIndexTest extends TestCase
{
    public function testIndexIsInitiallyEmpty(): void
    {
        $nodeIndex = new NodeIndex();
        $this->assertEmpty($nodeIndex->getName());
        $this->assertEmpty($nodeIndex->getType());
        $this->assertEmpty($nodeIndex->getFor());
    }

    public function testName(): void
    {
        $nodeIndex = new NodeIndex();
        $this->assertNull($nodeIndex->getName());
        $nodeIndex->setName('some_name');
        $this->assertSame('some_name', $nodeIndex->getName());
        $nodeIndex->setName(null);
        $this->assertNull($nodeIndex->getName());
    }

    public function testType(): void
    {
        $nodeIndex = new NodeIndex();
        $this->assertNull($nodeIndex->getType());
        $nodeIndex->setType('BTREE');
        $this->assertSame('BTREE', $nodeIndex->getType());
        $nodeIndex->setType(null);
        $this->assertNull($nodeIndex->getType());
    }

    public function testFor(): void
    {
        $nodeIndex = new NodeIndex();
        $this->assertNull($nodeIndex->getFor());
        $nodeIndex->setFor('SomeNodeLabel');
        $this->assertSame('SomeNodeLabel', $nodeIndex->getFor());
        $nodeIndex->setFor(null);
        $this->assertNull($nodeIndex->getFor());
    }

    public function testToString(): void
    {
        $nodeIndex = (new NodeIndex())
            ->setName('name')
            ->setType('BTREE')
            ->setFor('Node')
            ->addProperty('id', 'not null');
        $this->assertSame("BTREE INDEX name FOR (node:Node) ON (node.id)", (string) $nodeIndex);
    }

    public function testIsEqualTo(): void
    {
        $nodeIndex = (new NodeIndex())
            ->setName('name')
            ->setFor('Node')
            ->setType('BTREE')
            ->addProperty('id', 'not null');
        $otherNodeIndex = (clone $nodeIndex)
            ->setName('other_index');
        $this->assertFalse($nodeIndex->isEqualTo(123));
        $this->assertFalse($nodeIndex->isEqualTo($otherNodeIndex));
        $this->assertTrue($nodeIndex->isEqualTo($nodeIndex));
        $this->assertTrue($nodeIndex->isEqualTo(clone $nodeIndex));
    }
}

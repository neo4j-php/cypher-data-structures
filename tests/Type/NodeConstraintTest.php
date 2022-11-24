<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\NodeConstraint;

class NodeConstraintTest extends TestCase
{
    public function testIndexIsInitiallyEmpty(): void
    {
        $nodeConstraint = new NodeConstraint();
        $this->assertEmpty($nodeConstraint->getName());
        $this->assertEmpty($nodeConstraint->getType());
        $this->assertEmpty($nodeConstraint->getFor());
    }

    public function testName(): void
    {
        $nodeConstraint = new NodeConstraint();
        $this->assertNull($nodeConstraint->getName());
        $nodeConstraint->setName('some_name');
        $this->assertSame('some_name', $nodeConstraint->getName());
        $nodeConstraint->setName(null);
        $this->assertNull($nodeConstraint->getName());
    }

    public function testType(): void
    {
        $nodeConstraint = new NodeConstraint();
        $this->assertNull($nodeConstraint->getType());
        $nodeConstraint->setType('SOME_TYPE');
        $this->assertSame('SOME_TYPE', $nodeConstraint->getType());
        $nodeConstraint->setType(null);
        $this->assertNull($nodeConstraint->getType());
    }

    public function testFor(): void
    {
        $nodeConstraint = new NodeConstraint();
        $this->assertNull($nodeConstraint->getFor());
        $nodeConstraint->setFor('SomeNodeLabel');
        $this->assertSame('SomeNodeLabel', $nodeConstraint->getFor());
        $nodeConstraint->setFor(null);
        $this->assertNull($nodeConstraint->getFor());
    }

    public function testToString(): void
    {
        $nodeConstraint = (new NodeConstraint())
            ->setName('name')
            ->setType('UNIQUE')
            ->setFor('Node')
            ->addProperty('id', 'not null')
            ->addOption('option', 'value');
        // todo
        $this->assertSame('todo', (string) $nodeConstraint);
    }

    public function testIsEqualTo(): void
    {
        $nodeConstraint = (new NodeConstraint())
            ->setName('name')
            ->setFor('Node')
            ->setType('UNIQUE');
        $otherNodeConstraint = (clone $nodeConstraint)
            ->setName('other_index');
        $this->assertFalse($nodeConstraint->isEqualTo(123));
        $this->assertFalse($nodeConstraint->isEqualTo($otherNodeConstraint));
        $this->assertTrue($nodeConstraint->isEqualTo($nodeConstraint));
        $this->assertTrue($nodeConstraint->isEqualTo(clone $nodeConstraint));
    }
}

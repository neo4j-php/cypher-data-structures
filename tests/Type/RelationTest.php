<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\Relation;

class RelationTest extends TestCase
{
    public function testRelationIsInitiallyEmpty(): void
    {
        $relation = new Relation();
        $this->assertNull($relation->getType());
        $this->assertEmpty($relation->getProperties());
        $this->assertEmpty($relation->getIdentifiers());
        $this->assertNull($relation->getStartNode());
        $this->assertNull($relation->getEndNode());
    }

    public function testRelationType(): void
    {
        $relation = new Relation();
        $relation->setType('SOME_TYPE');
        $this->assertSame('SOME_TYPE', $relation->getType());
        $relation->setType(null);
        $this->assertNull($relation->getType());
    }

    public function testRelationStartNode(): void
    {
        $relation = new Relation();
        $this->assertNull($relation->getStartNode());
        $node = (new Node())
            ->addLabel('Node')
            ->addProperty('id', 123)
            ->addIdentifier('id');
        $relation->setStartNode($node);
        $this->assertSame($node, $relation->getStartNode());
        $relation->setStartNode(null);
        $this->assertNull($relation->getStartNode());
    }

    public function testRelationEndNode(): void
    {
        $relation = new Relation();
        $this->assertNull($relation->getEndNode());
        $node = (new Node())
            ->addLabel('Node')
            ->addProperty('id', 123)
            ->addIdentifier('id');
        $relation->setEndNode($node);
        $this->assertSame($node, $relation->getEndNode());
        $relation->setEndNode(null);
        $this->assertNull($relation->getEndNode());
    }

    public function testToString(): void
    {
        $relation = (new Relation())
            ->setType('SOME_TYPE')
            ->addProperty('id', 123)
            ->addProperty('hello', 'world')
            ->addIdentifier('id')
            ->setStartNode(
                (new Node())
                    ->addLabel('StartNode')
            )
            ->setEndNode(
                (new Node())
                    ->addLabel('EndNode')
            );
        $this->assertSame("(:StartNode)-[:SOME_TYPE {hello: 'world', id: 123}]->(:EndNode)", (string) $relation);
    }

    public function testIsEqualTo(): void
    {
        $relation = (new Relation())
            ->setType('SOME_TYPE')
            ->addProperty('id', 123)
            ->addProperty('hello', 'world')
            ->addIdentifier('id')
            ->setStartNode(
                (new Node())
                    ->addLabel('StartNode')
            )
            ->setEndNode(
                (new Node())
                    ->addLabel('EndNode')
            );
        $this->assertFalse($relation->isEqualTo(123));
        $this->assertFalse($relation->isEqualTo(
            (new Relation())
                ->setStartNode(new Node())
                ->setEndNode(new Node())
        ));
        $this->assertTrue($relation->isEqualTo($relation));
        $this->assertTrue($relation->isEqualTo(clone $relation));
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\Relation;

class NodeTest extends TestCase
{
    public function testNodeIsInitiallyEmpty(): void
    {
        $node = new Node();
        $this->assertEmpty($node->getLabels());
        $this->assertEmpty($node->getProperties());
        $this->assertEmpty($node->getIdentifiers());
        $this->assertEmpty($node->getRelations());
    }

    public function testNodeLabels(): void
    {
        $node = new Node();

        $node->addLabel('labelA');
        $this->assertCount(1, $node->getLabels());
        $this->assertSame('labelA', $node->getLabels()[0]);
        $this->assertTrue($node->hasLabel('labelA'));

        $labels = ['labelB', 'labelC', 'labelD'];
        $node->addLabels($labels);
        $this->assertCount(4, $node->getLabels());

        $node->removeLabel('labelC');
        $this->assertCount(3, $node->getLabels());
        $this->assertFalse($node->hasLabel('labelC'));

        $node->removeLabels();
        $this->assertEmpty($node->getLabels());
    }

    public function testNodeRelations(): void
    {
        $node = (new Node())
            ->addLabel('startNode')
            ->addProperty('id', 100)
            ->addIdentifier('id');

        $relation = (new Relation())
            ->setStartNode($node)
            ->setEndNode(new Node());

        $this->assertEmpty($node->getRelations());

        $node->addRelation($relation);
        $this->assertCount(1, $node->getRelations());
        $this->assertSame($relation, $node->getRelations()[0]);
        $this->assertTrue($node->hasRelation($relation));

        $relations = [
            (new Relation())
                ->setStartNode($node)
                ->setEndNode(
                    (new Node())
                        ->addLabel('otherNode')
                        ->addProperty('id', 101)
                        ->addIdentifier('id')
                )
                ->addProperty('id', 201)
                ->addIdentifier('id'),
            (new Relation())
                ->setStartNode($node)
                ->setEndNode(
                    (new Node())
                        ->addLabel('otherNode')
                        ->addProperty('id', 102)
                        ->addIdentifier('id')
                )
                ->addProperty('id', 202)
                ->addIdentifier('id'),
        ];

        $this->assertFalse($node->hasRelation($relations[0]));
        $node->addRelations($relations);
        $this->assertCount(3, $node->getRelations());

        $node->removeRelation($relations[1]);
        $this->assertCount(2, $node->getRelations());

        $node->removeRelations();
        $this->assertEmpty($node->getRelations());
    }

    public function testExceptionOnMissingStartNode(): void
    {
        $node = new Node();
        $relation = (new Relation())
            ->setEndNode(new Node());
        $this->expectExceptionMessage('Start node must be set');
        $this->expectException(InvalidArgumentException::class);
        $node->addRelation($relation);
    }

    public function testExceptionOnMissingEndNode(): void
    {
        $node = new Node();
        $relation = (new Relation())
            ->setStartNode(new Node());
        $this->expectExceptionMessage('End node must be set');
        $this->expectException(InvalidArgumentException::class);
        $node->addRelation($relation);
    }

    public function testExceptionOnRelationIsNotConnectedToNode(): void
    {
        $node = (new Node())
            ->addProperty('id', 1234)
            ->addIdentifier('id');
        $relation = (new Relation())
            ->setStartNode(new Node())
            ->setEndNode(new Node());
        $this->expectExceptionMessage('Adding a relation to a node requires that either the start node or the end node must be the same as the node itself.');
        $this->expectException(InvalidArgumentException::class);
        $node->addRelation($relation);
    }

    public function testToString(): void
    {
        $node = (new Node())
            ->addLabels(['LabelA', 'LabelZ', 'LabelC', 'discouraged Style'])
            ->addProperty('id', 123)
            ->addIdentifier('id');
        // todo fix backticks in ToCypherHelper class
        $this->assertSame("(:LabelA:LabelC:LabelZ:discouraged Style {id: '123'})", (string) $node);
    }

    public function testIsEqualTo(): void
    {
        $node = (new Node())
            ->addLabel('Node')
            ->addProperty('id', 123)
            ->addIdentifier('id');
        $this->assertFalse($node->isEqualTo(123));
        $this->assertFalse($node->isEqualTo(new Node()));
        $this->assertTrue($node->isEqualTo($node));
        $this->assertTrue($node->isEqualTo(clone $node));
    }
}

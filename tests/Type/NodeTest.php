<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\OGM\Node;
use Syndesi\CypherDataStructures\Type\OGM\NodeLabel;
use Syndesi\CypherDataStructures\Type\OGM\NodeLabelStorage;
use Syndesi\CypherDataStructures\Type\OGM\Relation;
use Syndesi\CypherDataStructures\Type\OGM\RelationType;
use Syndesi\CypherDataStructures\Type\OGM\WeakRelation;
use Syndesi\CypherDataStructures\Type\OGM\WeakRelationStorage;
use Syndesi\CypherDataStructures\Type\PropertyName;

class NodeTest extends TestCase
{
    public function testNodeLabels(): void
    {
        $node = new Node();
        $node->addNodeLabel(new NodeLabel('SomeLabel'));
        $this->assertSame(1, $node->getNodeLabels()->count());
        $this->assertTrue($node->hasNodeLabel(new NodeLabel('SomeLabel')));
        $this->assertFalse($node->hasNodeLabel(new NodeLabel('NotExistingLabel')));

        $nodeLabelStorage = new NodeLabelStorage();
        $nodeLabelStorage->attach(new NodeLabel('OtherLabel'));
        $nodeLabelStorage->attach(new NodeLabel('AnotherLabel'));

        $node->addNodeLabels($nodeLabelStorage);
        $this->assertSame(3, $node->getNodeLabels()->count());
        $node->removeNodeLabel(new NodeLabel('OtherLabel'));
        $this->assertSame(2, $node->getNodeLabels()->count());
        $node->clearNodeLabels();
        $this->assertSame(0, $node->getNodeLabels()->count());
    }

    public function testToString(): void
    {
        $node = new Node();
        $node->addNodeLabel(new NodeLabel('NodeA'));
        $node->addProperty(new PropertyName('id'), 'A');
        $node->addProperty(new PropertyName('propertyA'), 'value A');
        $node->addIdentifier(new PropertyName('id'));
        $this->assertSame("(:NodeA {id: 'A', propertyA: 'value A'})", (string) $node);
        $otherNode = new Node();
        $this->assertSame('()', (string) $otherNode);
    }

    public function testIsEqualTo(): void
    {
        $nodeA = new Node();
        $nodeA->addNodeLabel(new NodeLabel('NodeA'));
        $nodeA->addProperty(new PropertyName('id'), 'A');
        $nodeA->addProperty(new PropertyName('propertyA'), 'value A');
        $nodeA->addIdentifier(new PropertyName('id'));

        $nodeB = new Node();
        $nodeB->addNodeLabel(new NodeLabel('NodeA'));
        $nodeB->addProperty(new PropertyName('id'), 'A');
        $nodeB->addProperty(new PropertyName('propertyB'), 'value B');
        $nodeB->addIdentifier(new PropertyName('id'));

        $nodeC = new Node();
        $nodeC->addNodeLabel(new NodeLabel('NodeC'));
        $nodeC->addProperty(new PropertyName('id'), 'C');
        $nodeC->addProperty(new PropertyName('propertyC'), 'value C');
        $nodeC->addIdentifier(new PropertyName('id'));

        $this->assertTrue($nodeA->isEqualTo($nodeB));
        $this->assertTrue($nodeB->isEqualTo($nodeA));
        $this->assertFalse($nodeA->isEqualTo($nodeC));
        $this->assertFalse($nodeC->isEqualTo($nodeA));
        $this->assertFalse($nodeA->isEqualTo(new stdClass()));
        $this->assertFalse($nodeA->isEqualTo('some string'));
    }

    public function testRelations(): void
    {
        $node = new Node();
        $node->addNodeLabel(new NodeLabel('Node'));
        $node->addProperty(new PropertyName('id'), 1234);
        $node->addIdentifier(new PropertyName('id'));

        $otherNode = new Node();
        $otherNode->addNodeLabel(new NodeLabel('OtherNode'));
        $otherNode->addProperty(new PropertyName('id'), 4321);
        $otherNode->addIdentifier(new PropertyName('id'));

        $relationA = new Relation();
        $relationA->setStartNode($node);
        $relationA->setEndNode($otherNode);
        $relationA->setRelationType(new RelationType('TYPE'));

        $relationB = new Relation();
        $relationB->setStartNode($otherNode);
        $relationB->setEndNode($node);
        $relationB->setRelationType(new RelationType('TYPE'));

        $relationC = new Relation();
        $relationC->setStartNode($node);
        $relationC->setEndNode($node);
        $relationC->setRelationType(new RelationType('TYPE'));

        $node->addRelation($relationA);
        $this->assertSame(1, $node->getRelations()->count());
        $this->assertTrue($node->hasRelation($relationA));

        $node->addRelation($relationB);
        $this->assertSame(2, $node->getRelations()->count());
        $this->assertTrue($node->hasRelation($relationB));

        $node->addRelation($relationC);
        $this->assertSame(3, $node->getRelations()->count());
        $this->assertTrue($node->hasRelation($relationC));

        $node->removeRelation($relationA);
        $this->assertFalse($node->hasRelation($relationA));
        $this->assertSame(2, $node->getRelations()->count());

        $node->clearRelations();
        $this->assertFalse($node->hasRelation($relationB));
        $this->assertSame(0, $node->getRelations()->count());

        $weakRelationStorage = new WeakRelationStorage();
        $weakRelationStorage->attach(WeakRelation::create($relationA));
        $weakRelationStorage->attach(WeakRelation::create($relationB));
        $weakRelationStorage->attach(WeakRelation::create($relationC));
        $node->addRelations($weakRelationStorage);
        $this->assertSame(3, $node->getRelations()->count());
    }

    public function testAddInvalidRelation(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $node = new Node();
        $node->addNodeLabel(new NodeLabel('Node'));
        $node->addProperty(new PropertyName('id'), 1234);
        $node->addIdentifier(new PropertyName('id'));

        $otherNode = new Node();
        $otherNode->addNodeLabel(new NodeLabel('OtherNode'));
        $otherNode->addProperty(new PropertyName('id'), 4321);
        $otherNode->addIdentifier(new PropertyName('id'));

        $relation = new Relation();
        $relation->setStartNode($node);
        $relation->setEndNode($otherNode);
        $relation->setRelationType(new RelationType('TYPE'));

        $weakRelation = WeakRelation::create($relation);
        unset($relation);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Reference of type 'Syndesi\CypherDataStructures\Contract\WeakRelationInterface' is already null.");

        $node->addRelation($weakRelation);
    }

    public function testAddRelationWithoutBeingStartOrEnd(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $node = new Node();
        $node->addNodeLabel(new NodeLabel('Node'));
        $node->addProperty(new PropertyName('id'), 1234);
        $node->addIdentifier(new PropertyName('id'));

        $otherNode = new Node();
        $otherNode->addNodeLabel(new NodeLabel('OtherNode'));
        $otherNode->addProperty(new PropertyName('id'), 4321);
        $otherNode->addIdentifier(new PropertyName('id'));

        $relation = new Relation();
        $relation->setStartNode($otherNode);
        $relation->setEndNode($otherNode);
        $relation->setRelationType(new RelationType('TYPE'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Adding a relation to a node requires that either the start node or the end node must be the same as the node itself.");

        $node->addRelation($relation);
    }

    public function testAddRelationWithoutStartNode(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $node = new Node();
        $node->addNodeLabel(new NodeLabel('Node'));
        $node->addProperty(new PropertyName('id'), 1234);
        $node->addIdentifier(new PropertyName('id'));

        $relation = new Relation();
        $relation->setEndNode($node);
        $relation->setRelationType(new RelationType('TYPE'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Contract\NodeInterface', got type 'null'");

        $node->addRelation($relation);
    }

    public function testAddRelationWithoutEndNode(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $node = new Node();
        $node->addNodeLabel(new NodeLabel('Node'));
        $node->addProperty(new PropertyName('id'), 1234);
        $node->addIdentifier(new PropertyName('id'));

        $relation = new Relation();
        $relation->setStartNode($node);
        $relation->setRelationType(new RelationType('TYPE'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Contract\NodeInterface', got type 'null'");

        $node->addRelation($relation);
    }

    public function testHasInvalidRelation(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $node = new Node();

        $relation = new Relation();
        $weakRelation = WeakRelation::create($relation);
        unset($relation);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Reference of type 'Syndesi\CypherDataStructures\Contract\WeakRelationInterface' is already null.");

        $node->hasRelation($weakRelation);
    }

    public function testRemoveInvalidRelation(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $node = new Node();

        $relation = new Relation();
        $weakRelation = WeakRelation::create($relation);
        unset($relation);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Reference of type 'Syndesi\CypherDataStructures\Contract\WeakRelationInterface' is already null.");

        $node->removeRelation($weakRelation);
    }
}

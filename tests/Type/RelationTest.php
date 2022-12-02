<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\OGM\Node;
use Syndesi\CypherDataStructures\Type\OGM\NodeLabel;
use Syndesi\CypherDataStructures\Type\OGM\Relation;
use Syndesi\CypherDataStructures\Type\OGM\RelationType;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

class RelationTest extends TestCase
{
    public function testProperties(): void
    {
        $relation = new Relation();
        $relation->addProperty(new PropertyName('someProperty'), 'some value');
        $this->assertSame(1, $relation->getProperties()->count());
        $this->assertTrue($relation->hasProperty(new PropertyName('someProperty')));
        $this->assertFalse($relation->hasProperty(new PropertyName('notExistingProperty')));
        $this->assertSame('some value', $relation->getProperty(new PropertyName('someProperty')));

        $propertyStorage = new PropertyStorage();
        $propertyStorage->attach(new PropertyName('otherProperty'), 'other value');
        $propertyStorage->attach(new PropertyName('anotherProperty'), 'another value');

        $relation->addProperties($propertyStorage);
        $this->assertSame(3, $relation->getProperties()->count());
        $relation->removeProperty(new PropertyName('otherProperty'));
        $this->assertSame(2, $relation->getProperties()->count());
        $relation->clearProperties();
        $this->assertSame(0, $relation->getProperties()->count());
    }

    public function testRelationType(): void
    {
        $relation = new Relation();
        $relation->setRelationType(new RelationType('SOME_TYPE'));
        $this->assertSame('SOME_TYPE', $relation->getRelationType()->getRelationType());
    }

    public function testIdentifier(): void
    {
        $relation = new Relation();
        $relation->addProperty(new PropertyName('someProperty'), 'some value');
        $relation->addIdentifier(new PropertyName('someProperty'));
        $this->assertTrue($relation->hasIdentifier(new PropertyName('someProperty')));
        $this->assertFalse($relation->hasIdentifier(new PropertyName('notExistingProperty')));
        $this->assertSame(1, $relation->getIdentifiers()->count());
        $this->assertSame('some value', $relation->getIdentifier(new PropertyName('someProperty')));

        $relation->addProperty(new PropertyName('otherProperty'), 'other value');
        $relation->addProperty(new PropertyName('anotherProperty'), 'another value');
        $identifierStorage = new PropertyStorage();
        $identifierStorage->attach(new PropertyName('otherProperty'));
        $identifierStorage->attach(new PropertyName('anotherProperty'));
        $relation->addIdentifiers($identifierStorage);
        $this->assertSame(3, $relation->getIdentifiers()->count());
        $relation->removeIdentifier(new PropertyName('otherProperty'));
        $this->assertSame(2, $relation->getIdentifiers()->count());
        $relation->clearIdentifier();
        $this->assertSame(0, $relation->getIdentifiers()->count());
    }

    public function testRemoveIdentifyingProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $relation = new Relation();
        $relation->addProperty(new PropertyName('someProperty'), 'some value');
        $relation->addIdentifier(new PropertyName('someProperty'));
        $this->expectException(InvalidArgumentException::class);
        $relation->removeProperty(new PropertyName('someProperty'));
    }

    public function testRemoveAllIdentifyingProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $relation = new Relation();
        $relation->addProperty(new PropertyName('someProperty'), 'some value');
        $relation->addIdentifier(new PropertyName('someProperty'));
        $this->expectException(InvalidArgumentException::class);
        $relation->clearProperties();
    }

    public function testAddIdentifierWithoutProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $relation = new Relation();
        $this->expectException(InvalidArgumentException::class);
        $relation->addIdentifier(new PropertyName('someProperty'));
    }

    public function testStartNode(): void
    {
        $node = new Node();
        $node->addNodeLabel(new NodeLabel('SomeNode'))
            ->addProperty(new PropertyName('id'), 123)
            ->addIdentifier(new PropertyName('id'));
        $relation = new Relation();
        $relation->setStartNode($node);
        $this->assertSame($node, $relation->getStartNode());
    }

    public function testEndNode(): void
    {
        $node = new Node();
        $node->addNodeLabel(new NodeLabel('SomeNode'))
            ->addProperty(new PropertyName('id'), 123)
            ->addIdentifier(new PropertyName('id'));
        $relation = new Relation();
        $relation->setEndNode($node);
        $this->assertSame($node, $relation->getEndNode());
    }

    public function testToString(): void
    {
        $relation = new Relation();
        $relation->setRelationType(new RelationType('TYPE'));
        $relation->addProperty(new PropertyName('id'), 'A');
        $relation->addProperty(new PropertyName('value'), 'A');
        $this->assertSame("()-[:TYPE {id: 'A', value: 'A'}]->()", (string) $relation);
    }

    public function testIsEqual(): void
    {
        $relationA = new Relation();
        $relationA->setRelationType(new RelationType('TYPE_A'));
        $relationA->addProperty(new PropertyName('id'), 'A');
        $relationA->addProperty(new PropertyName('value'), 'A');

        $relationB = new Relation();
        $relationB->setRelationType(new RelationType('TYPE_A'));
        $relationB->addProperty(new PropertyName('id'), 'A');
        $relationB->addProperty(new PropertyName('value'), 'B');

        $relationC = new Relation();
        $relationC->setRelationType(new RelationType('TYPE_C'));
        $relationC->addProperty(new PropertyName('id'), 'C');
        $relationC->addProperty(new PropertyName('value'), 'C');

        $this->assertTrue($relationA->isEqualTo($relationB));
        $this->assertTrue($relationB->isEqualTo($relationA));
        $this->assertFalse($relationA->isEqualTo($relationC));
        $this->assertFalse($relationC->isEqualTo($relationA));
        $this->assertFalse($relationA->isEqualTo(new stdClass()));
        $this->assertFalse($relationA->isEqualTo('some string'));
    }
}

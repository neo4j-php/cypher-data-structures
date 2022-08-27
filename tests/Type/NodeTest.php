<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\NodeLabelStorage;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

class NodeTest extends TestCase
{
    public function testProperties(): void
    {
        $node = new Node();
        $node->addProperty(new PropertyName('someProperty'), 'some value');
        $this->assertSame(1, $node->getProperties()->count());
        $this->assertTrue($node->hasProperty(new PropertyName('someProperty')));
        $this->assertFalse($node->hasProperty(new PropertyName('notExistingProperty')));
        $this->assertSame('some value', $node->getProperty(new PropertyName('someProperty')));

        $propertyStorage = new PropertyStorage();
        $propertyStorage->attach(new PropertyName('otherProperty'), 'other value');
        $propertyStorage->attach(new PropertyName('anotherProperty'), 'another value');

        $node->addProperties($propertyStorage);
        $this->assertSame(3, $node->getProperties()->count());
        $node->removeProperty(new PropertyName('otherProperty'));
        $this->assertSame(2, $node->getProperties()->count());
        $node->clearProperties();
        $this->assertSame(0, $node->getProperties()->count());
    }

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

    public function testIdentifier(): void
    {
        $node = new Node();
        $node->addProperty(new PropertyName('someProperty'), 'some value');
        $node->addIdentifier(new PropertyName('someProperty'));
        $this->assertTrue($node->hasIdentifier(new PropertyName('someProperty')));
        $this->assertFalse($node->hasIdentifier(new PropertyName('notExistingProperty')));
        $this->assertSame(1, $node->getIdentifiers()->count());
        $this->assertSame('some value', $node->getIdentifier(new PropertyName('someProperty')));

        $node->addProperty(new PropertyName('otherProperty'), 'other value');
        $node->addProperty(new PropertyName('anotherProperty'), 'another value');
        $identifierStorage = new PropertyStorage();
        $identifierStorage->attach(new PropertyName('otherProperty'));
        $identifierStorage->attach(new PropertyName('anotherProperty'));
        $node->addIdentifiers($identifierStorage);
        $this->assertSame(3, $node->getIdentifiers()->count());
        $node->removeIdentifier(new PropertyName('otherProperty'));
        $this->assertSame(2, $node->getIdentifiers()->count());
        $node->clearIdentifier();
        $this->assertSame(0, $node->getIdentifiers()->count());
    }

    public function testRemoveIdentifyingProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $node = new Node();
        $node->addProperty(new PropertyName('someProperty'), 'some value');
        $node->addIdentifier(new PropertyName('someProperty'));
        $this->expectException(InvalidArgumentException::class);
        $node->removeProperty(new PropertyName('someProperty'));
    }

    public function testRemoveAllIdentifyingProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $node = new Node();
        $node->addProperty(new PropertyName('someProperty'), 'some value');
        $node->addIdentifier(new PropertyName('someProperty'));
        $this->expectException(InvalidArgumentException::class);
        $node->clearProperties();
    }

    public function testAddIdentifierWithoutProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $node = new Node();
        $this->expectException(InvalidArgumentException::class);
        $node->addIdentifier(new PropertyName('someProperty'));
    }
}

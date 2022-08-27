<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

class NodeTest extends TestCase
{
    public function testEmptyNode(): void
    {
        $node = new Node();
        $this->assertTrue(true);
    }

    public function testProperties(): void
    {
        $node = new Node();
        $node->addProperty(new PropertyName('someProperty'), 'some value');
        $this->assertSame(1, $node->getProperties()->count());
        $this->assertTrue($node->hasProperty(new PropertyName('someProperty')));
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
    }
}

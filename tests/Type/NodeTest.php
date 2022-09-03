<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\NodeLabelStorage;
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
}

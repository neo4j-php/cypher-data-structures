<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\NodeLabelStorage;

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
}

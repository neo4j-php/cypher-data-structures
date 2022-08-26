<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\NodeLabelStorage;

class NodeLabelStorageTest extends TestCase
{
    public function testValidNodeLabelStorage(): void
    {
        $nodeLabelStorage = new NodeLabelStorage();
        $this->assertSame(0, $nodeLabelStorage->count());

        $nodeLabelA = new NodeLabel('NodeLabelA');
        $nodeLabelB = new NodeLabel('NodeLabelB');
        $nodeLabelC = new NodeLabel('NodeLabelC');

        $nodeLabelStorage->attach($nodeLabelA, 123);
        $nodeLabelStorage->attach($nodeLabelB, 'some string');
        $nodeLabelStorage->attach($nodeLabelC, ['some' => 'array']);

        $this->assertSame(3, $nodeLabelStorage->count());

        // test access with same object
        $this->assertSame(123, $nodeLabelStorage->offsetGet($nodeLabelA));

        // test access with different object
        $newNodeLabelB = new NodeLabel('NodeLabelB');
        $this->assertSame('some string', $nodeLabelStorage->offsetGet($newNodeLabelB));

        // test current
        foreach ($nodeLabelStorage as $key) {
            $this->assertInstanceOf(NodeLabelInterface::class, $key);
        }
    }

    public function testInvalidNodeLabelStorage(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $nodeLabelStorage = new NodeLabelStorage();

        $this->expectExceptionMessage("Syndesi\CypherDataStructures\Contract\NodeLabelInterface', got type 'stdClass'");
        $this->expectException(InvalidArgumentException::class);
        $nodeLabelStorage->attach(new stdClass());
    }

    public function testDuplicateNodeLabelStorage(): void
    {
        $nodeLabelStorage = new NodeLabelStorage();
        $nodeLabelA = new NodeLabel('SomeNodeLabel');
        $nodeLabelB = new NodeLabel('SomeNodeLabel');
        $nodeLabelStorage->attach($nodeLabelA, 'initial value');
        $nodeLabelStorage->attach($nodeLabelB, 'updated value');
        $this->assertSame('updated value', $nodeLabelStorage->offsetGet($nodeLabelA));
    }
}
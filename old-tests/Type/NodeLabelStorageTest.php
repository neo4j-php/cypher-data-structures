<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use SplObjectStorage;
use stdClass;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Exception\LogicException;
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

    public function testInternalTypeMismatch(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $instance = new class() extends NodeLabelStorage {
            public function getHash(object $object): string
            {
                return SplObjectStorage::getHash($object);
            }
        };

        $object = new stdClass();
        $instance->attach($object);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Internal type mismatch, expected type 'Syndesi\CypherDataStructures\Contract\NodeLabelInterface', got type 'stdClass'");

        foreach ($instance as $key) {
            $this->assertInstanceOf(NodeLabel::class, $key);
        }
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

    public function testIsEqualTo(): void
    {
        $nodeLabelA = new NodeLabel('LabelA');
        $nodeLabelB = new NodeLabel('LabelB');
        $nodeLabelC = new NodeLabel('LabelC');

        $nodeLabelStorageA = new NodeLabelStorage();
        $nodeLabelStorageA->attach($nodeLabelA);

        $nodeLabelStorageB = new NodeLabelStorage();
        $nodeLabelStorageB->attach($nodeLabelA, 'ignored value');

        $nodeLabelStorageC = new NodeLabelStorage();
        $nodeLabelStorageC->attach($nodeLabelB);
        $nodeLabelStorageC->attach($nodeLabelC);

        $this->assertTrue($nodeLabelStorageA->isEqualTo($nodeLabelStorageB));
        $this->assertTrue($nodeLabelStorageB->isEqualTo($nodeLabelStorageA));

        $this->assertFalse($nodeLabelStorageA->isEqualTo($nodeLabelStorageC));
        $this->assertFalse($nodeLabelStorageC->isEqualTo($nodeLabelStorageA));

        $this->assertFalse($nodeLabelStorageA->isEqualTo(new stdClass()));
    }
}

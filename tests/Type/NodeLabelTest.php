<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\NodeLabel;

class NodeLabelTest extends TestCase
{
    public function validNodeLabelProvider(): array
    {
        return [
            ['Valid'],
            ['ValidNodeLabel'],
            ['_Valid'],
            ['ValidNode123Label'],
        ];
    }

    /**
     * @dataProvider validNodeLabelProvider
     */
    public function testValidNodeLabel(string $nodeLabel): void
    {
        $property = new NodeLabel($nodeLabel);
        $this->assertSame($nodeLabel, $property->getNodeLabel());
        $this->assertSame($nodeLabel, (string) $property);
    }

    public function invalidNodeLabelProvider(): array
    {
        return [
            ['invalid'],
            ['invalidNodeLabel'],
            ['invalid Node Label'],
            ['123label'],
            ['invalid_NodeLabel'],
        ];
    }

    /**
     * @dataProvider invalidNodeLabelProvider
     */
    public function testInvalidNodeLabel(string $nodeLabel): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage(sprintf(
            "Expected string '%s' to follow regex for Camel case with optional underscore (_) at beginning, '/^_?([A-Z][a-z0-9]+)((\d)|([A-Z0-9][a-z0-9]+))*([A-Z])?$/'",
            $nodeLabel
        ));
        $this->expectException(InvalidArgumentException::class);
        new NodeLabel($nodeLabel);
    }

    public function testIsEqualTo(): void
    {
        $nodeLabelA = new NodeLabel('SomeNodeLabel');
        $nodeLabelB = new NodeLabel('SomeNodeLabel');
        $nodeLabelC = new NodeLabel('OtherNodeLabel');
        $this->assertTrue($nodeLabelA->isEqualTo($nodeLabelB));
        $this->assertTrue($nodeLabelB->isEqualTo($nodeLabelA));
        $this->assertFalse($nodeLabelA->isEqualTo($nodeLabelC));
        $this->assertFalse($nodeLabelC->isEqualTo($nodeLabelA));
        $this->assertFalse($nodeLabelA->isEqualTo('something else'));
    }
}

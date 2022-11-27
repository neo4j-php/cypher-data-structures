<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToStringHelper;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\NodeConstraint;
use Syndesi\CypherDataStructures\Type\NodeIndex;
use Syndesi\CypherDataStructures\Type\Relation;
use Syndesi\CypherDataStructures\Type\RelationConstraint;
use Syndesi\CypherDataStructures\Type\RelationIndex;

class ToStringHelperTest extends TestCase
{
    public function testMustNameBeEscaped(): void
    {
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('abc'));
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('Abc'));
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('ABC'));
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('abc123'));
        $this->assertFalse(ToStringHelper::mustNameBeEscaped('abc_123'));
        $this->assertTrue(ToStringHelper::mustNameBeEscaped('123'));
        $this->assertTrue(ToStringHelper::mustNameBeEscaped('abc.abc'));
        $this->assertTrue(ToStringHelper::mustNameBeEscaped('abc abc'));
    }

    public function escapeStringProvider(): array
    {
        return [
            ['hello world', 'hello world'],
            ["hello ' world", "hello \' world"],                    // hello ' world
            ["hello \' world", "hello \' world"],                   // hello \' world
            ["hello \\' world", "hello \' world"],                  // hello \' world
            ["hello \\\' world", "hello \\\\\' world"],             // hello \\' world
            ["hello \\\\' world", "hello \\\\\' world"],            // hello \\' world
            ["hello \\\\\' world", "hello \\\\\' world"],           // hello \\\' world
            ["hello \\\\\\' world", "hello \\\\\' world"],          // hello \\\' world
            ["hello \\\\\\\' world", "hello \\\\\\\\\' world"],     // hello \\\\' world
            ["hello \\\\\\\\' world", "hello \\\\\\\\\' world"],    // hello \\\\' world
        ];
    }

    /**
     * @dataProvider escapeStringProvider
     */
    public function testEscapeCharacter(string $string, string $output): void
    {
        $result = ToStringHelper::escapeString($string);
        $this->assertSame($output, $result);
    }

    public function testInvalidEscapeCharacter(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage('Escape character must be of length 1, got \'--\'');
        $this->expectException(InvalidArgumentException::class);
        ToStringHelper::escapeString('some string', '--');
    }

    public function valueToStringProvider(): array
    {
        return [
            [null, 'null'],
            [true, 'true'],
            [false, 'false'],
            [0, '0'],
            [123, '123'],
            [1.23, '1.23'],
            ['some string', "'some string'"],
            ['some \'string', "'some \'string'"],
            [[1, 2, 3], '[1, 2, 3]'],
            [[1, 3, 2], '[1, 2, 3]'],
            [[0, null, 'hi', 'abc'], "[0, null, 'abc', 'hi']"],
        ];
    }

    /**
     * @dataProvider valueToStringProvider
     */
    public function testValueToString($value, $string): void
    {
        $this->assertSame($string, ToStringHelper::valueToString($value));
    }

    public function testPropertyArrayToString(): void
    {
        $properties = [
            'int' => 123,
            'float' => 123.4,
            'string' => 'string',
            'stringWithSpace' => 'hello world',
            'stringWithDot' => 'hello.world',
            'stringWithBacktick' => 'hello\'world',
            'array' => ['a', 'b', 'c'],
            'problematic .\' name' => 'hi :D',
        ];
        $this->assertSame("array: ['a', 'b', 'c'], float: 123.4, int: 123, `problematic .\' name`: 'hi :D', string: 'string', stringWithBacktick: 'hello\'world', stringWithDot: 'hello.world', stringWithSpace: 'hello world'", ToStringHelper::propertyArrayToString($properties));
    }

    public function testLabelsToString(): void
    {
        $labels = ['a', 'z', 'b', 'E', '_c', '012', 'problematic label', '#label'];
        $this->assertSame(":`#label`:`012`:E:_c:a:b:`problematic label`:z", ToStringHelper::labelsToString($labels));
    }

    public function testNodeToString(): void
    {
        $node = (new Node())
            ->addLabels(['NodeA', 'NodeZ', 'NodeC', '_node', '01235'])
            ->addProperties([
                'id' => 123,
                'hello' => 'world :D',
            ])
            ->addIdentifier('id');
        $this->assertSame("(:`01235`:NodeA:NodeC:NodeZ:_node {hello: 'world :D', id: 123})", ToStringHelper::nodeToString($node));
        $this->assertSame("(:`01235`:NodeA:NodeC:NodeZ:_node {id: 123})", ToStringHelper::nodeToString($node, true));
    }

    public function testRelationToString(): void
    {
        $nodeA = (new Node())
            ->addLabel('NodeA')
            ->addProperty('id', 123)
            ->addIdentifier('id');
        $nodeB = (new Node())
            ->addLabel('NodeB')
            ->addProperty('id', 321)
            ->addIdentifier('id');
        $relation = (new Relation())
            ->setStartNode($nodeA)
            ->setEndNode($nodeB)
            ->setType('RELATION')
            ->addProperties([
                'id' => 123,
                'hello' => 'world :D',
            ])
            ->addIdentifier('id');

        $this->assertSame("(:NodeA {id: 123})-[:RELATION {hello: 'world :D', id: 123}]->(:NodeB {id: 321})", ToStringHelper::relationToString($relation));
        $this->assertSame("[:RELATION {hello: 'world :D', id: 123}]", ToStringHelper::relationToString($relation, withNodes: false));
        $this->assertSame("[:RELATION {id: 123}]", ToStringHelper::relationToString($relation, identifying: true, withNodes: false));
    }

    public function testNodeConstraintToString(): void
    {
        $nodeConstraint = (new NodeConstraint())
            ->setName('some_name')
            ->setFor('Node')
            ->setType('UNIQUE')
            ->addProperty('id');

        $this->assertSame("CONSTRAINT some_name FOR (node:Node) REQUIRE node.id IS UNIQUE", ToStringHelper::nodeConstraintToString($nodeConstraint));

        $nodeConstraint->addProperty('hello');

        $this->assertSame("CONSTRAINT some_name FOR (node:Node) REQUIRE (node.hello, node.id) IS UNIQUE", ToStringHelper::nodeConstraintToString($nodeConstraint));
    }

    public function testRelationConstraintToString(): void
    {
        $relationConstraint = (new RelationConstraint())
            ->setName('some_name')
            ->setFor('RELATION')
            ->setType('UNIQUE')
            ->addProperty('id');

        $this->assertSame("CONSTRAINT some_name FOR ()-[relation:RELATION]-() REQUIRE relation.id IS UNIQUE", ToStringHelper::relationConstraintToString($relationConstraint));

        $relationConstraint->addProperty('hello');

        $this->assertSame("CONSTRAINT some_name FOR ()-[relation:RELATION]-() REQUIRE (relation.hello, relation.id) IS UNIQUE", ToStringHelper::relationConstraintToString($relationConstraint));
    }

    public function testNodeIndexToString(): void
    {
        $nodeIndex = (new NodeIndex())
            ->setName('some_name')
            ->setFor('Node')
            ->setType('BTREE')
            ->addProperty('id');

        $this->assertSame("BTREE INDEX some_name FOR (node:Node) ON (node.id)", ToStringHelper::nodeIndexToString($nodeIndex));

        $nodeIndex->addProperty('hello');

        $this->assertSame("BTREE INDEX some_name FOR (node:Node) ON (node.hello, node.id)", ToStringHelper::nodeIndexToString($nodeIndex));
    }

    public function testRelationIndexToString(): void
    {
        $relationIndex = (new RelationIndex())
            ->setName('some_name')
            ->setFor('RELATION')
            ->setType('BTREE')
            ->addProperty('id');

        $this->assertSame("BTREE INDEX some_name FOR ()-[relation:RELATION]-() ON (relation.id)", ToStringHelper::relationIndexToString($relationIndex));

        $relationIndex->addProperty('hello');

        $this->assertSame("BTREE INDEX some_name FOR ()-[relation:RELATION]-() ON (relation.hello, relation.id)", ToStringHelper::relationIndexToString($relationIndex));
    }
}

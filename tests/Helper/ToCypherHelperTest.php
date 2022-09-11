<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Type\Constraint;
use Syndesi\CypherDataStructures\Type\ConstraintName;
use Syndesi\CypherDataStructures\Type\ConstraintType;
use Syndesi\CypherDataStructures\Type\Index;
use Syndesi\CypherDataStructures\Type\IndexName;
use Syndesi\CypherDataStructures\Type\IndexType;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\NodeLabelStorage;
use Syndesi\CypherDataStructures\Type\OptionName;
use Syndesi\CypherDataStructures\Type\OptionStorage;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\PropertyStorage;
use Syndesi\CypherDataStructures\Type\Relation;
use Syndesi\CypherDataStructures\Type\RelationType;

class ToCypherHelperTest extends TestCase
{
    public function testEmptyPropertyStorageToCypherPropertyString(): void
    {
        $propertyStorage = new PropertyStorage();
        $generatedString = ToCypherHelper::propertyStorageToCypherPropertyString($propertyStorage);
        $this->assertSame('', $generatedString);
    }

    public function testEmptyValuePropertyStorageToCypherPropertyString(): void
    {
        $propertyStorage = new PropertyStorage();
        $propertyStorage->attach(new PropertyName('a'));
        $generatedString = ToCypherHelper::propertyStorageToCypherPropertyString($propertyStorage);
        $this->assertSame("a: ''", $generatedString);
    }

    public function testSinglePropertyStorageToCypherPropertyString(): void
    {
        $propertyStorage = new PropertyStorage();
        $propertyStorage->attach(new PropertyName('a'), 'value a');
        $generatedString = ToCypherHelper::propertyStorageToCypherPropertyString($propertyStorage);
        $this->assertSame("a: 'value a'", $generatedString);
    }

    public function testMultiplePropertyStorageToCypherPropertyString(): void
    {
        $propertyStorage = new PropertyStorage();
        $propertyStorage->attach(new PropertyName('a'), 'value a');
        $propertyStorage->attach(new PropertyName('z'), 'value z');
        $propertyStorage->attach(new PropertyName('b'), "value which ' needs to be escaped");
        $propertyStorage->attach(new PropertyName('_internalZ'), "hi");
        $propertyStorage->attach(new PropertyName('_internalA'), "hi");
        $generatedString = ToCypherHelper::propertyStorageToCypherPropertyString($propertyStorage);
        $this->assertSame("_internalA: 'hi', _internalZ: 'hi', a: 'value a', b: 'value which \\' needs to be escaped', z: 'value z'", $generatedString);
    }

    public function testEmptyNodeLabelStorageToCypherLabelString(): void
    {
        $nodeLabelStorage = new NodeLabelStorage();
        $generatedString = ToCypherHelper::nodeLabelStorageToCypherLabelString($nodeLabelStorage);
        $this->assertSame('', $generatedString);
    }

    public function testSingleNodeLabelStorageToCypherLabelString(): void
    {
        $nodeLabelStorage = new NodeLabelStorage();
        $nodeLabelStorage->attach(new NodeLabel('Label'));
        $generatedString = ToCypherHelper::nodeLabelStorageToCypherLabelString($nodeLabelStorage);
        $this->assertSame(':Label', $generatedString);
    }

    public function testMultipleNodeLabelStorageToCypherLabelString(): void
    {
        $nodeLabelStorage = new NodeLabelStorage();
        $nodeLabelStorage->attach(new NodeLabel('LabelA'));
        $nodeLabelStorage->attach(new NodeLabel('LabelC'));
        $nodeLabelStorage->attach(new NodeLabel('LabelB'));
        $nodeLabelStorage->attach(new NodeLabel('_InternalZ'));
        $nodeLabelStorage->attach(new NodeLabel('_InternalA'));
        $generatedString = ToCypherHelper::nodeLabelStorageToCypherLabelString($nodeLabelStorage);
        $this->assertSame(':_InternalA:_InternalZ:LabelA:LabelB:LabelC', $generatedString);
    }

    public function testNodeToCypherString(): void
    {
        $node = new Node();
        $node->addNodeLabel(new NodeLabel('SomeNode'));
        $node->addProperty(new PropertyName('propertyA'), 'value A');
        $node->addProperty(new PropertyName('propertyB'), 'value B');
        $node->addProperty(new PropertyName('propertyC'), 'value C');
        $node->addProperty(new PropertyName('propertyD'), 'value D');
        $node->addIdentifier(new PropertyName('propertyA'));
        $node->addIdentifier(new PropertyName('propertyC'));
        $this->assertSame("(:SomeNode {propertyA: 'value A', propertyB: 'value B', propertyC: 'value C', propertyD: 'value D'})", ToCypherHelper::nodeToCypherString($node));
        $this->assertSame("(:SomeNode {propertyA: 'value A', propertyC: 'value C'})", ToCypherHelper::nodeToIdentifyingCypherString($node));
        $node->clearNodeLabels();
        $this->assertSame("({propertyA: 'value A', propertyB: 'value B', propertyC: 'value C', propertyD: 'value D'})", ToCypherHelper::nodeToCypherString($node));
        $this->assertSame("({propertyA: 'value A', propertyC: 'value C'})", ToCypherHelper::nodeToIdentifyingCypherString($node));
        $node->clearIdentifier();
        $this->assertSame("({propertyA: 'value A', propertyB: 'value B', propertyC: 'value C', propertyD: 'value D'})", ToCypherHelper::nodeToCypherString($node));
        $this->assertSame("()", ToCypherHelper::nodeToIdentifyingCypherString($node));
        $node->clearProperties();
        $this->assertSame("()", ToCypherHelper::nodeToCypherString($node));
    }

    public function testRelationToCypherString(): void
    {
        $startNode = new Node();
        $startNode->addNodeLabel(new NodeLabel('StartNode'));
        $startNode->addProperty(new PropertyName('id'), 1234);
        $startNode->addIdentifier(new PropertyName('id'));

        $endNode = new Node();
        $endNode->addNodeLabel(new NodeLabel('EndNode'));
        $endNode->addProperty(new PropertyName('id'), 4321);
        $endNode->addIdentifier(new PropertyName('id'));

        $relation = new Relation();
        $relation->setStartNode($startNode);
        $relation->setEndNode($endNode);
        $relation->setRelationType(new RelationType('SOME_TYPE'));
        $relation->addProperty(new PropertyName('id'), 123);
        $relation->addProperty(new PropertyName('somethingElse'), 'some non id value');
        $relation->addIdentifier(new PropertyName('id'));

        $this->assertSame("(:StartNode {id: '1234'})-[:SOME_TYPE {id: '123', somethingElse: 'some non id value'}]->(:EndNode {id: '4321'})", ToCypherHelper::relationToCypherString($relation));
        $this->assertSame("[:SOME_TYPE {id: '123', somethingElse: 'some non id value'}]", ToCypherHelper::relationToCypherString($relation, withNodes: false));
        $this->assertSame("(:StartNode {id: '1234'})-[:SOME_TYPE {id: '123'}]->(:EndNode {id: '4321'})", ToCypherHelper::relationToIdentifyingCypherString($relation));
        $this->assertSame("[:SOME_TYPE {id: '123'}]", ToCypherHelper::relationToIdentifyingCypherString($relation, false));
        $this->assertSame("[:SOME_TYPE {id: '123'}]", ToCypherHelper::relationToIdentifyingCypherString($relation, false));
    }

    public function testEmptyRelationToCypherString(): void
    {
        $relation = new Relation();
        $this->assertSame("()-[]->()", ToCypherHelper::relationToCypherString($relation));
        $this->assertSame("[]", ToCypherHelper::relationToCypherString($relation, withNodes: false));
        $this->assertSame("()-[]->()", ToCypherHelper::relationToIdentifyingCypherString($relation));
        $this->assertSame("[]", ToCypherHelper::relationToIdentifyingCypherString($relation, false));
    }

    public function valueToStringProvider(): array
    {
        $indexConfig = new OptionStorage();
        $indexConfig->attach(new OptionName('spatial.cartesian.min'), [-100.0, -100.0]);
        $indexConfig->attach(new OptionName('spatial.cartesian.max'), [100.0, 100.0]);
        $optionStorage = new OptionStorage();
        $optionStorage->attach(new OptionName('indexConfig'), $indexConfig);

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
            [new OptionName('someOption'), 'someOption'],
            [$optionStorage, '{indexConfig: {`spatial.cartesian.max`: [100, 100], `spatial.cartesian.min`: [-100, -100]}}'],
        ];
    }

    /**
     * @dataProvider valueToStringProvider
     */
    public function testValueToString($value, $string): void
    {
        $this->assertSame($string, ToCypherHelper::valueToString($value));
    }

    public function testInvalidObjectValueToString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected type 'Stringable', got type 'stdClass'");
        ToCypherHelper::valueToString(new stdClass());
    }

    public function testInvalidValueToString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to cast value to string");
        ToCypherHelper::valueToString(tmpfile());
    }

    public function testEmptyOptionStorageToCypherOptionString(): void
    {
        $optionStorage = new OptionStorage();
        $generatedString = ToCypherHelper::optionStorageToCypherString($optionStorage);
        $this->assertSame('{}', $generatedString);
    }

    public function testEmptyValueOptionStorageToCypherOptionString(): void
    {
        $optionStorage = new OptionStorage();
        $optionStorage->attach(new OptionName('a'));
        $generatedString = ToCypherHelper::optionStorageToCypherString($optionStorage);
        $this->assertSame("{a: null}", $generatedString);
    }

    public function testSingleOptionStorageToCypherOptionString(): void
    {
        $optionStorage = new OptionStorage();
        $optionStorage->attach(new OptionName('a'), 'value a');
        $generatedString = ToCypherHelper::optionStorageToCypherString($optionStorage);
        $this->assertSame("{a: 'value a'}", $generatedString);
    }

    public function testMultipleOptionStorageToCypherOptionString(): void
    {
        $optionStorage = new OptionStorage();
        $optionStorage->attach(new OptionName('a'), 1);
        $optionStorage->attach(new OptionName('z'), true);
        $optionStorage->attach(new OptionName('b'), "value which ' needs to be escaped");
        $optionStorage->attach(new OptionName('something.else'), "hi");
        $generatedString = ToCypherHelper::optionStorageToCypherString($optionStorage);
        $this->assertSame("{a: 1, b: 'value which \\' needs to be escaped', `something.else`: 'hi', z: true}", $generatedString);
    }

    public function testConstraintToCypherString(): void
    {
        $constraint = new Constraint();
        $constraint
            ->setConstraintType(ConstraintType::UNIQUE)
            ->setConstraintName(new ConstraintName('some_name'))
            ->setFor(new NodeLabel('SomeLabel'))
            ->addProperty(new PropertyName('id'));

        $this->assertSame('CONSTRAINT some_name FOR (element:SomeLabel) REQUIRE (element.id) IS UNIQUE', ToCypherHelper::constraintToCypherString($constraint));

        $constraint->addOption(new OptionName('option'), 123);
        $this->assertSame('CONSTRAINT some_name FOR (element:SomeLabel) REQUIRE (element.id) IS UNIQUE OPTIONS {option: 123}', ToCypherHelper::constraintToCypherString($constraint));
        $constraint->addOption(new OptionName('something.else'));
        $this->assertSame('CONSTRAINT some_name FOR (element:SomeLabel) REQUIRE (element.id) IS UNIQUE OPTIONS {option: 123, `something.else`: null}', ToCypherHelper::constraintToCypherString($constraint));
    }

    public function testWithoutForConstraintToCypherString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Contract\NodeLabelInterface|Syndesi\CypherDataStructures\Contract\RelationTypeInterface', got type 'null'");
        $this->expectException(InvalidArgumentException::class);
        $constraint = new Constraint();
        $constraint
            ->setConstraintType(ConstraintType::UNIQUE)
            ->setConstraintName(new ConstraintName('some_name'))
            ->addProperty(new PropertyName('id'));
        ToCypherHelper::constraintToCypherString($constraint);
    }

    public function testWithoutConstraintNameConstraintToCypherString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Contract\ConstraintNameInterface', got type 'null'");
        $this->expectException(InvalidArgumentException::class);
        $constraint = new Constraint();
        $constraint
            ->setConstraintType(ConstraintType::UNIQUE)
            ->setFor(new NodeLabel('SomeLabel'))
            ->addProperty(new PropertyName('id'));
        ToCypherHelper::constraintToCypherString($constraint);
    }

    public function testWithoutConstraintTypeConstraintToCypherString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Type\ConstraintType', got type 'null'");
        $this->expectException(InvalidArgumentException::class);
        $constraint = new Constraint();
        $constraint
            ->setConstraintName(new ConstraintName('some_name'))
            ->setFor(new NodeLabel('SomeLabel'))
            ->addProperty(new PropertyName('id'));
        ToCypherHelper::constraintToCypherString($constraint);
    }

    public function testWithoutPropertiesConstraintToCypherString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage("At least one property is required");
        $this->expectException(InvalidArgumentException::class);
        $constraint = new Constraint();
        $constraint
            ->setConstraintType(ConstraintType::UNIQUE)
            ->setConstraintName(new ConstraintName('some_name'))
            ->setFor(new NodeLabel('SomeLabel'));
        ToCypherHelper::constraintToCypherString($constraint);
    }

    public function testIndexToCypherString(): void
    {
        $index = new Index();
        $index
            ->setIndexType(IndexType::BTREE)
            ->setIndexName(new IndexName('some_name'))
            ->setFor(new NodeLabel('SomeLabel'))
            ->addProperty(new PropertyName('id'));

        $this->assertSame('BTREE INDEX some_name FOR (element:SomeLabel) ON (element.id)', ToCypherHelper::indexToCypherString($index));

        $index->addOption(new OptionName('option'), 123);
        $this->assertSame('BTREE INDEX some_name FOR (element:SomeLabel) ON (element.id) OPTIONS {option: 123}', ToCypherHelper::indexToCypherString($index));
        $index->addOption(new OptionName('something.else'));
        $this->assertSame('BTREE INDEX some_name FOR (element:SomeLabel) ON (element.id) OPTIONS {option: 123, `something.else`: null}', ToCypherHelper::indexToCypherString($index));
    }

    public function testWithoutForIndexToCypherString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Contract\NodeLabelInterface|Syndesi\CypherDataStructures\Contract\RelationTypeInterface', got type 'null'");
        $this->expectException(InvalidArgumentException::class);
        $index = new Index();
        $index
            ->setIndexType(IndexType::BTREE)
            ->setIndexName(new IndexName('some_name'))
            ->addProperty(new PropertyName('id'));
        ToCypherHelper::indexToCypherString($index);
    }

    public function testWithoutIndexNameIndexToCypherString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Contract\IndexNameInterface', got type 'null'");
        $this->expectException(InvalidArgumentException::class);
        $index = new Index();
        $index
            ->setIndexType(IndexType::BTREE)
            ->setFor(new NodeLabel('SomeLabel'))
            ->addProperty(new PropertyName('id'));
        ToCypherHelper::indexToCypherString($index);
    }

    public function testWithoutIndexTypeIndexToCypherString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Type\IndexType', got type 'null'");
        $this->expectException(InvalidArgumentException::class);
        $index = new Index();
        $index
            ->setIndexName(new IndexName('some_name'))
            ->setFor(new NodeLabel('SomeLabel'))
            ->addProperty(new PropertyName('id'));
        ToCypherHelper::indexToCypherString($index);
    }

    public function testWithoutPropertiesIndexToCypherString(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage("At least one property is required");
        $this->expectException(InvalidArgumentException::class);
        $index = new Index();
        $index
            ->setIndexType(IndexType::BTREE)
            ->setIndexName(new IndexName('some_name'))
            ->setFor(new NodeLabel('SomeLabel'));
        ToCypherHelper::indexToCypherString($index);
    }
}

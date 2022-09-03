<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\NodeLabelStorage;
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
    }

    public function testEmptyRelationToCypherString(): void
    {
        $relation = new Relation();
        $this->assertSame("()-[]->()", ToCypherHelper::relationToCypherString($relation));
        $this->assertSame("[]", ToCypherHelper::relationToCypherString($relation, withNodes: false));
        $this->assertSame("()-[]->()", ToCypherHelper::relationToIdentifyingCypherString($relation));
        $this->assertSame("[]", ToCypherHelper::relationToIdentifyingCypherString($relation, false));
    }
}

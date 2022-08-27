<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\NodeLabelStorage;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

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
}

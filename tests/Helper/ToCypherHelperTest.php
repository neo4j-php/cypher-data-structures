<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
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
        $generatedString = ToCypherHelper::propertyStorageToCypherPropertyString($propertyStorage);
        $this->assertSame("a: 'value a', b: 'value which \\' needs to be escaped', z: 'value z'", $generatedString);
    }
}

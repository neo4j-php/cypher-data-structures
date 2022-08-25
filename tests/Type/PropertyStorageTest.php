<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

class PropertyStorageTest extends TestCase
{
    public function testValidPropertyStorage(): void
    {
        $propertyStorage = new PropertyStorage();
        $this->assertSame(0, $propertyStorage->count());

        $propertyNameA = new PropertyName('_propertyNameA');
        $propertyNameB = new PropertyName('_propertyNameB');
        $propertyNameC = new PropertyName('_propertyNameC');

        $propertyStorage->attach($propertyNameA, 123);
        $propertyStorage->attach($propertyNameB, 'some string');
        $propertyStorage->attach($propertyNameC, ['some' => 'array']);

        $this->assertSame(3, $propertyStorage->count());

        // test access with same object
        $this->assertSame(123, $propertyStorage->offsetGet($propertyNameA));

        // test access with different object
        $newPropertyNameB = new PropertyName('_propertyNameB');
        $this->assertSame('some string', $propertyStorage->offsetGet($newPropertyNameB));

        // test current
        foreach ($propertyStorage as $key) {
            $this->assertInstanceOf(PropertyNameInterface::class, $key);
        }
    }

    public function testInvalidPropertyStorage(): void
    {
        $propertyStorage = new PropertyStorage();

        $this->expectExceptionMessage("Syndesi\CypherDataStructures\Contract\PropertyNameInterface', got type 'stdClass'");
        $this->expectException(InvalidArgumentException::class);
        $propertyStorage->attach(new stdClass());
    }

    public function testDuplicatePropertyStorage(): void
    {
        $propertyStorage = new PropertyStorage();
        $propertyNameA = new PropertyName('_somePropertyName');
        $propertyNameB = new PropertyName('_somePropertyName');
        $propertyStorage->attach($propertyNameA, 'initial value');
        $propertyStorage->attach($propertyNameB, 'updated value');
        $this->assertSame('updated value', $propertyStorage->offsetGet($propertyNameA));
    }
}

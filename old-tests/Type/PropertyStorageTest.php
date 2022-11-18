<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use SplObjectStorage;
use stdClass;
use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Exception\LogicException;
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
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $propertyStorage = new PropertyStorage();

        $this->expectExceptionMessage("Syndesi\CypherDataStructures\Contract\PropertyNameInterface', got type 'stdClass'");
        $this->expectException(InvalidArgumentException::class);
        $propertyStorage->attach(new stdClass());
    }

    public function testInternalTypeMismatch(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $instance = new class() extends PropertyStorage {
            public function getHash(object $object): string
            {
                return SplObjectStorage::getHash($object);
            }
        };

        $object = new stdClass();
        $instance->attach($object);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Internal type mismatch, expected type 'Syndesi\CypherDataStructures\Contract\PropertyNameInterface', got type 'stdClass'");

        foreach ($instance as $key) {
            $this->assertInstanceOf(PropertyName::class, $key);
        }
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

    public function testIsEqualTo(): void
    {
        $propertyNameA = new PropertyName('propertyA');
        $propertyNameB = new PropertyName('propertyB');
        $propertyNameC = new PropertyName('propertyC');

        $propertyStorageA = new PropertyStorage();
        $propertyStorageA->attach($propertyNameA, 'some value');

        $propertyStorageB = new PropertyStorage();
        $propertyStorageB->attach($propertyNameA, 'some value');

        $propertyStorageC = new PropertyStorage();
        $propertyStorageC->attach($propertyNameB, 'some value');
        $propertyStorageC->attach($propertyNameC, 'some value');

        $this->assertTrue($propertyStorageA->isEqualTo($propertyStorageB));
        $this->assertTrue($propertyStorageB->isEqualTo($propertyStorageA));

        $this->assertFalse($propertyStorageA->isEqualTo($propertyStorageC));
        $this->assertFalse($propertyStorageC->isEqualTo($propertyStorageA));

        $this->assertFalse($propertyStorageA->isEqualTo(new stdClass()));
    }

    public function testToString(): void
    {
        $propertyStorage = new PropertyStorage();
        $propertyStorage->attach(new PropertyName('a'), 'some value');
        $propertyStorage->attach(new PropertyName('b'), 'some value');
        $propertyStorage->attach(new PropertyName('c'), 'some value');
        $this->assertSame("a: 'some value', b: 'some value', c: 'some value'", (string) $propertyStorage);
    }
}

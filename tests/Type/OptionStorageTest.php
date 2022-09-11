<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use SplObjectStorage;
use stdClass;
use Syndesi\CypherDataStructures\Contract\OptionNameInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Exception\LogicException;
use Syndesi\CypherDataStructures\Type\OptionName;
use Syndesi\CypherDataStructures\Type\OptionStorage;

class OptionStorageTest extends TestCase
{
    public function testValidOptionStorage(): void
    {
        $optionStorage = new OptionStorage();
        $this->assertSame(0, $optionStorage->count());

        $optionNameA = new OptionName('_optionNameA');
        $optionNameB = new OptionName('_optionNameB');
        $optionNameC = new OptionName('_optionNameC');

        $optionStorage->attach($optionNameA, 123);
        $optionStorage->attach($optionNameB, 'some string');
        $optionStorage->attach($optionNameC, ['some' => 'array']);

        $this->assertSame(3, $optionStorage->count());

        // test access with same object
        $this->assertSame(123, $optionStorage->offsetGet($optionNameA));

        // test access with different object
        $newOptionNameB = new OptionName('_optionNameB');
        $this->assertSame('some string', $optionStorage->offsetGet($newOptionNameB));

        // test current
        foreach ($optionStorage as $key) {
            $this->assertInstanceOf(OptionNameInterface::class, $key);
        }
    }

    public function testInvalidOptionStorage(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $optionStorage = new OptionStorage();

        $this->expectExceptionMessage("Syndesi\CypherDataStructures\Contract\OptionNameInterface', got type 'stdClass'");
        $this->expectException(InvalidArgumentException::class);
        $optionStorage->attach(new stdClass());
    }

    public function testInternalTypeMismatch(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $instance = new class() extends OptionStorage {
            public function getHash(object $object): string
            {
                return SplObjectStorage::getHash($object);
            }
        };

        $object = new stdClass();
        $instance->attach($object);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Internal type mismatch, expected type 'Syndesi\CypherDataStructures\Contract\OptionNameInterface', got type 'stdClass'");

        foreach ($instance as $key) {
            $this->assertInstanceOf(OptionName::class, $key);
        }
    }

    public function testDuplicateOptionStorage(): void
    {
        $optionStorage = new OptionStorage();
        $optionNameA = new OptionName('_someOptionName');
        $optionNameB = new OptionName('_someOptionName');
        $optionStorage->attach($optionNameA, 'initial value');
        $optionStorage->attach($optionNameB, 'updated value');
        $this->assertSame('updated value', $optionStorage->offsetGet($optionNameA));
    }

    public function testIsEqualTo(): void
    {
        $optionNameA = new OptionName('optionA');
        $optionNameB = new OptionName('optionB');
        $optionNameC = new OptionName('optionC');

        $optionStorageA = new OptionStorage();
        $optionStorageA->attach($optionNameA, 'some value');

        $optionStorageB = new OptionStorage();
        $optionStorageB->attach($optionNameA, 'some value');

        $optionStorageC = new OptionStorage();
        $optionStorageC->attach($optionNameB, 'some value');
        $optionStorageC->attach($optionNameC, 'some value');

        $this->assertTrue($optionStorageA->isEqualTo($optionStorageB));
        $this->assertTrue($optionStorageB->isEqualTo($optionStorageA));

        $this->assertFalse($optionStorageA->isEqualTo($optionStorageC));
        $this->assertFalse($optionStorageC->isEqualTo($optionStorageA));

        $this->assertFalse($optionStorageA->isEqualTo(new stdClass()));
    }

    public function testToString(): void
    {
        $optionStorage = new OptionStorage();
        $optionStorage->attach(new OptionName('a'), 'some value');
        $optionStorage->attach(new OptionName('b'), 'some value');
        $optionStorage->attach(new OptionName('c'), 'some value');
        $this->assertSame("a: 'some value', b: 'some value', c: 'some value'", (string) $optionStorage);
    }
}

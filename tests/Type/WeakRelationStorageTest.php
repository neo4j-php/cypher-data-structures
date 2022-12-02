<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use SplObjectStorage;
use stdClass;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Exception\LogicException;
use Syndesi\CypherDataStructures\Type\OGM\Relation;
use Syndesi\CypherDataStructures\Type\OGM\RelationType;
use Syndesi\CypherDataStructures\Type\OGM\WeakRelation;
use Syndesi\CypherDataStructures\Type\OGM\WeakRelationStorage;

class WeakRelationStorageTest extends TestCase
{
    public function testWeakRelationStorage(): void
    {
        $weakRelationStore = new WeakRelationStorage();

        $relationA = new Relation();
        $relationA->setRelationType(new RelationType('TYPE_A'));
        $weakRelationA = WeakRelation::create($relationA);

        $relationB = new Relation();
        $relationB->setRelationType(new RelationType('TYPE_B'));
        $weakRelationB = WeakRelation::create($relationB);

        $relationC = new Relation();
        $relationC->setRelationType(new RelationType('TYPE_C'));
        $weakRelationC = WeakRelation::create($relationC);

        $weakRelationStore->attach($weakRelationA);
        $this->assertSame(1, $weakRelationStore->count());
        $this->assertTrue($weakRelationStore->offsetExists($weakRelationA));
        $this->assertFalse($weakRelationStore->offsetExists($weakRelationB));

        $weakRelationStore->attach($weakRelationB);
        $weakRelationStore->attach($weakRelationC);
        $this->assertSame(3, $weakRelationStore->count());

        foreach ($weakRelationStore as $key) {
            $this->assertInstanceOf(WeakRelation::class, $key);
            $this->assertNotNull($key->get());
        }
        unset($relationA);
        unset($relationB);
        unset($relationC);
        foreach ($weakRelationStore as $key) {
            $this->assertInstanceOf(WeakRelation::class, $key);
            $this->assertNull($key->get());
        }
    }

    public function testInvalidType(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $weakRelationStorage = new WeakRelationStorage();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected type 'Syndesi\CypherDataStructures\Contract\WeakRelationInterface', got type 'stdClass'");

        $weakRelationStorage->attach(new stdClass());
    }

    public function testInternalTypeMismatch(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $instance = new class() extends WeakRelationStorage {
            public function getHash(object $object): string
            {
                return SplObjectStorage::getHash($object);
            }
        };

        $object = new stdClass();
        $instance->attach($object);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Internal type mismatch, expected type 'Syndesi\CypherDataStructures\Contract\WeakRelationInterface', got type 'stdClass'");

        foreach ($instance as $key) {
            $this->assertInstanceOf(WeakRelation::class, $key);
        }
    }

    public function testAlreadyNullReference(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }

        $relation = new Relation();
        $relation->setRelationType(new RelationType('TYPE_A'));
        $weakRelation = WeakRelation::create($relation);

        unset($relation);

        $weakRelationStorage = new WeakRelationStorage();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Reference of type 'Syndesi\CypherDataStructures\Contract\WeakRelationInterface' is already null.");

        $weakRelationStorage->attach($weakRelation);
    }

    public function testIsEqualTo(): void
    {
        $relationA = new Relation();
        $relationA->setRelationType(new RelationType('TYPE_A'));
        $weakRelationA = WeakRelation::create($relationA);

        $relationB = new Relation();
        $relationB->setRelationType(new RelationType('TYPE_B'));
        $weakRelationB = WeakRelation::create($relationB);

        $relationC = new Relation();
        $relationC->setRelationType(new RelationType('TYPE_C'));
        $weakRelationC = WeakRelation::create($relationC);

        $weakRelationStorageA = new WeakRelationStorage();
        $weakRelationStorageA->attach($weakRelationA);
        $weakRelationStorageA->attach($weakRelationB);

        $weakRelationStorageB = new WeakRelationStorage();
        $weakRelationStorageB->attach($weakRelationB);
        $weakRelationStorageB->attach($weakRelationA);

        $weakRelationStorageC = new WeakRelationStorage();
        $weakRelationStorageC->attach($weakRelationA);
        $weakRelationStorageC->attach($weakRelationC);

        $this->assertTrue($weakRelationStorageA->isEqualTo($weakRelationStorageB));
        $this->assertTrue($weakRelationStorageB->isEqualTo($weakRelationStorageA));
        $this->assertFalse($weakRelationStorageA->isEqualTo($weakRelationStorageC));
        $this->assertFalse($weakRelationStorageC->isEqualTo($weakRelationStorageA));
        $this->assertFalse($weakRelationStorageA->isEqualTo(new stdClass()));
        $this->assertFalse($weakRelationStorageA->isEqualTo('some string'));
    }
}

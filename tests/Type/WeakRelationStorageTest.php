<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use SplObjectStorage;
use stdClass;
use Syndesi\CypherDataStructures\Exception\LogicException;
use Syndesi\CypherDataStructures\Type\Relation;
use Syndesi\CypherDataStructures\Type\RelationType;
use Syndesi\CypherDataStructures\Type\WeakRelation;
use Syndesi\CypherDataStructures\Type\WeakRelationStorage;

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
//        $this->assertTrue($weakRelationStore->offsetExists($weakRelationA));
//        $this->assertFalse($weakRelationStore->offsetExists($weakRelationB));
//
//        $weakRelationStore->attach($weakRelationB);
//        $weakRelationStore->attach($weakRelationC);
//        $this->assertSame(3, $weakRelationStore->count());

        foreach ($weakRelationStore as $key) {
            $this->assertInstanceOf(WeakRelation::class, $key);
            $this->assertNotNull($key->get());
        }
        unset($relationA);
        foreach ($weakRelationStore as $key) {
            $this->assertInstanceOf(WeakRelation::class, $key);
            $this->assertNull($key->get());
        }
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
}

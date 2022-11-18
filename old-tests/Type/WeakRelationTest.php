<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\Relation;
use Syndesi\CypherDataStructures\Type\RelationType;
use Syndesi\CypherDataStructures\Type\WeakRelation;

class WeakRelationTest extends TestCase
{
    public function testReference(): void
    {
        $relation = new Relation();

        $weakRelation = WeakRelation::create($relation);
        $this->assertSame($relation, $weakRelation->get());
        unset($relation);
        $this->assertNull($weakRelation->get());
    }

    public function testIsEqualTo(): void
    {
        $relationA = new Relation();
        $relationA->setRelationType(new RelationType('TYPE_A'));
        $relationA->addProperty(new PropertyName('property'), 'A');
        $weakRelationA = WeakRelation::create($relationA);

        $relationB = new Relation();
        $relationB->setRelationType(new RelationType('TYPE_A'));
        $relationB->addProperty(new PropertyName('property'), 'B');
        $weakRelationB = WeakRelation::create($relationB);

        $relationC = new Relation();
        $relationC->setRelationType(new RelationType('TYPE_C'));
        $relationC->addProperty(new PropertyName('property'), 'C');
        $weakRelationC = WeakRelation::create($relationC);

        $this->assertTrue($weakRelationA->isEqualTo($weakRelationB));
        $this->assertTrue($weakRelationB->isEqualTo($weakRelationA));
        $this->assertFalse($weakRelationA->isEqualTo($weakRelationC));
        $this->assertFalse($weakRelationC->isEqualTo($weakRelationA));
        $this->assertFalse($weakRelationA->isEqualTo(new stdClass()));
        $this->assertFalse($weakRelationA->isEqualTo('some string'));

        unset($relationB);
        $this->assertNull($weakRelationB->get());
        $this->assertNull($weakRelationA->isEqualTo($weakRelationB));
        $this->assertNull($weakRelationB->isEqualTo($weakRelationA));
    }
}

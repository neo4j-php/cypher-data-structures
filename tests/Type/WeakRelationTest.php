<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\Relation;
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
}

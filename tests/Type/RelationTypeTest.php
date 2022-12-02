<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\OGM\RelationType;

class RelationTypeTest extends TestCase
{
    public function validRelationTypeProvider(): array
    {
        return [
            ['VALID'],
            ['VALID_RELATION_TYPE'],
            ['_VALID'],
            ['VALID_RELATION123_TYPE'],
        ];
    }

    /**
     * @dataProvider validRelationTypeProvider
     */
    public function testValidRelationType(string $relationType): void
    {
        $property = new RelationType($relationType);
        $this->assertSame($relationType, $property->getRelationType());
        $this->assertSame($relationType, (string) $property);
    }

    public function invalidRelationTypeProvider(): array
    {
        return [
            ['invalid'],
            ['invalidRelationType'],
            ['invalid Relation Type'],
            ['123type'],
            ['invalid_RelationType'],
        ];
    }

    /**
     * @dataProvider invalidRelationTypeProvider
     */
    public function testInvalidRelationType(string $relationType): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage(sprintf(
            "Expected string '%s' to follow regex for SCREAMING_SNAKE_CASE with optional underscore (_) at beginning, '/^_?[A-Z]([A-Z0-9]+_)*[A-Z0-9]*$/'",
            $relationType
        ));
        $this->expectException(InvalidArgumentException::class);
        new RelationType($relationType);
    }

    public function testIsEqualTo(): void
    {
        $relationTypeA = new RelationType('SOME_RELATION_TYPE');
        $relationTypeB = new RelationType('SOME_RELATION_TYPE');
        $relationTypeC = new RelationType('OTHER_RELATION_TYPE');
        $this->assertTrue($relationTypeA->isEqualTo($relationTypeB));
        $this->assertTrue($relationTypeB->isEqualTo($relationTypeA));
        $this->assertFalse($relationTypeA->isEqualTo($relationTypeC));
        $this->assertFalse($relationTypeC->isEqualTo($relationTypeA));
        $this->assertFalse($relationTypeA->isEqualTo('something else'));
    }
}

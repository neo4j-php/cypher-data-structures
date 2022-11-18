<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Trait;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Contract\HasIdentifiersInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Trait\IdentifiersTrait;

class IdentifiersTraitTest extends TestCase
{
    private function getTrait(): HasIdentifiersInterface
    {
        return new class() implements HasIdentifiersInterface {
            use IdentifiersTrait;
        };
    }

    public function testIdentifier(): void
    {
        $trait = $this->getTrait();
        $trait->addProperty('someProperty', 'some value');
        $trait->addIdentifier('someProperty');
        $this->assertTrue($trait->hasIdentifier('someProperty'));
        $this->assertFalse($trait->hasIdentifier('notExistingProperty'));
        $this->assertSame(1, count($trait->getIdentifiers()));
        $this->assertSame('some value', $trait->getIdentifier('someProperty'));

        $trait->addProperty('otherProperty', 'other value');
        $trait->addProperty('anotherProperty', 'another value');
        $trait->addIdentifiers([
            'otherProperty',
            'anotherProperty',
        ]);
        $this->assertCount(3, $trait->getIdentifiers());
        $trait->removeIdentifier('otherProperty');
        $this->assertCount(2, $trait->getIdentifiers());
        $trait->clearIdentifier();
        $this->assertCount(0, $trait->getIdentifiers());
    }

    public function testGetIdentifierWithPropertyValues(): void
    {
        $trait = $this->getTrait();
        $trait->addProperty('propertyA', 'value A');
        $trait->addProperty('propertyB', 'value B');
        $trait->addProperty('propertyC', 'value C');
        $trait->addProperty('propertyD', 'value D');
        $trait->addIdentifier('propertyA');
        $trait->addIdentifier('propertyC');
        $identifierWithPropertyValues = [...$trait->getIdentifiersWithPropertyValues()];
        $this->assertCount(2, $identifierWithPropertyValues);
        $this->assertArrayHasKey('propertyA', $identifierWithPropertyValues);
        $this->assertArrayHasKey('propertyC', $identifierWithPropertyValues);
        $this->assertSame('value A', $identifierWithPropertyValues['propertyA']);
        $this->assertSame('value C', $identifierWithPropertyValues['propertyC']);
    }

    public function testRemoveIdentifyingProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $trait = $this->getTrait();
        $trait->addProperty('someProperty', 'some value');
        $trait->addIdentifier('someProperty');
        $this->expectException(InvalidArgumentException::class);
        $trait->removeProperty('someProperty');
    }

    public function testClearIdentifyingProperties(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $trait = $this->getTrait();
        $trait->addProperty('someProperty', 'some value');
        $trait->addIdentifier('someProperty');
        $this->expectException(InvalidArgumentException::class);
        $trait->clearProperties();
    }

    public function testAddIdentifierWithoutProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $trait = $this->getTrait();
        $this->expectException(InvalidArgumentException::class);
        $trait->addIdentifier('someProperty');
    }
}

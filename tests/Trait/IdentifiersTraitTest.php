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
        $trait->removeIdentifiers();
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
        $identifierWithPropertyValues = [...$trait->getIdentifiers()];
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

    public function testRemoveIdentifyingProperties(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $trait = $this->getTrait();
        $trait->addProperty('someProperty', 'some value');
        $trait->addIdentifier('someProperty');
        $this->expectException(InvalidArgumentException::class);
        $trait->removeProperties();
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

    public function testProperties(): void
    {
        $trait = $this->getTrait();
        $trait->addProperty('someProperty', 'some value');
        $this->assertCount(1, $trait->getProperties());
        $this->assertTrue($trait->hasProperty('someProperty'));
        $this->assertFalse($trait->hasProperty('notExistingProperty'));
        $this->assertSame('some value', $trait->getProperty('someProperty'));

        $trait->addProperties([
            'otherProperty' => 'other value',
            'anotherProperty' => 'another value',
        ]);
        $this->assertCount(3, $trait->getProperties());
        $trait->removeProperty('otherProperty');
        $this->assertCount(2, $trait->getProperties());
        $trait->removeProperties();
        $this->assertCount(0, $trait->getProperties());
    }

    public function testRemovePropertyWhichIsAlsoIdentifier(): void
    {
        $trait = $this->getTrait()
            ->addProperty('id', 123)
            ->addIdentifier('id');
        $this->expectExceptionMessage("Unable to remove identifying property with name 'id' - remove identifier first");
        $this->expectException(InvalidArgumentException::class);
        $trait->removeProperty('id');
    }

    public function testRemovePropertiesWithExistingIdentifier(): void
    {
        $trait = $this->getTrait()
            ->addProperty('id', 123)
            ->addIdentifier('id');
        $this->expectExceptionMessage("Unable to remove all properties because identifiers are still defined");
        $this->expectException(InvalidArgumentException::class);
        $trait->removeProperties();
    }
}

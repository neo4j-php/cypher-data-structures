<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Trait;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Contract\HasPropertiesInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

class PropertiesTraitTest extends TestCase
{
    private function getTrait(): HasPropertiesInterface
    {
        return new class() implements HasPropertiesInterface {
            use PropertiesTrait;

            public function __construct()
            {
                $this->initPropertiesTrait();
            }
        };
    }

    public function testProperties(): void
    {
        $trait = $this->getTrait();
        $trait->addProperty(new PropertyName('someProperty'), 'some value');
        $this->assertSame(1, $trait->getProperties()->count());
        $this->assertTrue($trait->hasProperty(new PropertyName('someProperty')));
        $this->assertFalse($trait->hasProperty(new PropertyName('notExistingProperty')));
        $this->assertSame('some value', $trait->getProperty(new PropertyName('someProperty')));

        $propertyStorage = new PropertyStorage();
        $propertyStorage->attach(new PropertyName('otherProperty'), 'other value');
        $propertyStorage->attach(new PropertyName('anotherProperty'), 'another value');

        $trait->addProperties($propertyStorage);
        $this->assertSame(3, $trait->getProperties()->count());
        $trait->removeProperty(new PropertyName('otherProperty'));
        $this->assertSame(2, $trait->getProperties()->count());
        $trait->clearProperties();
        $this->assertSame(0, $trait->getProperties()->count());
    }

    public function testIdentifier(): void
    {
        $trait = $this->getTrait();
        $trait->addProperty(new PropertyName('someProperty'), 'some value');
        $trait->addIdentifier(new PropertyName('someProperty'));
        $this->assertTrue($trait->hasIdentifier(new PropertyName('someProperty')));
        $this->assertFalse($trait->hasIdentifier(new PropertyName('notExistingProperty')));
        $this->assertSame(1, $trait->getIdentifiers()->count());
        $this->assertSame('some value', $trait->getIdentifier(new PropertyName('someProperty')));

        $trait->addProperty(new PropertyName('otherProperty'), 'other value');
        $trait->addProperty(new PropertyName('anotherProperty'), 'another value');
        $identifierStorage = new PropertyStorage();
        $identifierStorage->attach(new PropertyName('otherProperty'));
        $identifierStorage->attach(new PropertyName('anotherProperty'));
        $trait->addIdentifiers($identifierStorage);
        $this->assertSame(3, $trait->getIdentifiers()->count());
        $trait->removeIdentifier(new PropertyName('otherProperty'));
        $this->assertSame(2, $trait->getIdentifiers()->count());
        $trait->clearIdentifier();
        $this->assertSame(0, $trait->getIdentifiers()->count());
    }

    public function testGetIdentifierWithPropertyValues(): void
    {
        $trait = $this->getTrait();
        $trait->addProperty(new PropertyName('propertyA'), 'value A');
        $trait->addProperty(new PropertyName('propertyB'), 'value B');
        $trait->addProperty(new PropertyName('propertyC'), 'value C');
        $trait->addProperty(new PropertyName('propertyD'), 'value D');
        $trait->addIdentifier(new PropertyName('propertyA'));
        $trait->addIdentifier(new PropertyName('propertyC'));
        $identifierWithPropertyValues = $trait->getIdentifiersWithPropertyValues();
        $this->assertSame(2, $identifierWithPropertyValues->count());
        $this->assertTrue($identifierWithPropertyValues->offsetExists(new PropertyName('propertyA')));
        $this->assertTrue($identifierWithPropertyValues->offsetExists(new PropertyName('propertyC')));
        $this->assertSame('value A', $identifierWithPropertyValues->offsetGet(new PropertyName('propertyA')));
        $this->assertSame('value C', $identifierWithPropertyValues->offsetGet(new PropertyName('propertyC')));
    }

    public function testRemoveIdentifyingProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $trait = $this->getTrait();
        $trait->addProperty(new PropertyName('someProperty'), 'some value');
        $trait->addIdentifier(new PropertyName('someProperty'));
        $this->expectException(InvalidArgumentException::class);
        $trait->removeProperty(new PropertyName('someProperty'));
    }

    public function testRemoveAllIdentifyingProperty(): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $trait = $this->getTrait();
        $trait->addProperty(new PropertyName('someProperty'), 'some value');
        $trait->addIdentifier(new PropertyName('someProperty'));
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
        $trait->addIdentifier(new PropertyName('someProperty'));
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Trait;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Contract\HasIdentifiersInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Trait\IdentifiersTrait;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

class IdentifiersTraitTest extends TestCase
{
    private function getTrait(): HasIdentifiersInterface
    {
        return new class() implements HasIdentifiersInterface {
            use IdentifiersTrait;

            public function __construct()
            {
                $this->initIdentifiersTrait();
            }
        };
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

    public function testClearIdentifyingProperties(): void
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

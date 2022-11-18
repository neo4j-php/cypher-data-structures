<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Trait;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Contract\HasPropertiesInterface;
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
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Trait;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Contract\HasPropertiesInterface;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;

class PropertiesTraitTest extends TestCase
{
    private function getTrait(): HasPropertiesInterface
    {
        return new class() implements HasPropertiesInterface {
            use PropertiesTrait;
        };
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
}

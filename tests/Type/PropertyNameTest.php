<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\PropertyName;

class PropertyNameTest extends TestCase
{
    public function validPropertyNameProvider(): array
    {
        return [
            ['valid'],
            ['validPropertyName'],
            ['_valid'],
            ['validPropertyName'],
            ['validProperty123Name'],
        ];
    }

    /**
     * @dataProvider validPropertyNameProvider
     */
    public function testValidPropertyName(string $propertyName): void
    {
        $property = new PropertyName($propertyName);
        $this->assertSame($propertyName, $property->getPropertyName());
        $this->assertSame($propertyName, (string) $property);
    }

    public function invalidPropertyNameProvider(): array
    {
        return [
            ['Invalid'],
            ['InvalidPropertyName'],
            ['invalid Property Name'],
            ['123property'],
            ['invalid_PropertyName'],
        ];
    }

    /**
     * @dataProvider invalidPropertyNameProvider
     */
    public function testInvalidPropertyName(string $propertyName): void
    {
        $this->expectExceptionMessage(sprintf(
            "Expected string '%s' to follow regex for camel case with optional underscore (_) at beginning, '/^_?[a-z]+((\d)|([A-Z0-9][a-z0-9]+))*([A-Z])?$/'",
            $propertyName
        ));
        $this->expectException(InvalidArgumentException::class);
        new PropertyName($propertyName);
    }

    public function testIsEqualTo(): void
    {
        $propertyNameA = new PropertyName('someProperty');
        $propertyNameB = new PropertyName('someProperty');
        $propertyNameC = new PropertyName('otherProperty');
        $this->assertTrue($propertyNameA->isEqualTo($propertyNameB));
        $this->assertTrue($propertyNameB->isEqualTo($propertyNameA));
        $this->assertFalse($propertyNameA->isEqualTo($propertyNameC));
        $this->assertFalse($propertyNameC->isEqualTo($propertyNameA));
        $this->assertFalse($propertyNameA->isEqualTo('something else'));
    }
}

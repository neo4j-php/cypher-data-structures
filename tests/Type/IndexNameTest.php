<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\IndexName;

class IndexNameTest extends TestCase
{
    public function validIndexNameProvider(): array
    {
        return [
            ['valid'],
            ['valid_index_name'],
            ['_valid'],
            ['valid_index123_name'],
        ];
    }

    /**
     * @dataProvider validIndexNameProvider
     */
    public function testValidIndexName(string $indexName): void
    {
        $property = new IndexName($indexName);
        $this->assertSame($indexName, $property->getIndexName());
        $this->assertSame($indexName, (string) $property);
    }

    public function invalidIndexNameProvider(): array
    {
        return [
            ['Invalid'],
            ['invalidIndexName'],
            ['invalid Index Name'],
            ['123name'],
        ];
    }

    /**
     * @dataProvider invalidIndexNameProvider
     */
    public function testInvalidIndexName(string $indexName): void
    {
        if (false !== getenv("LEAK")) {
            $this->markTestSkipped();
        }
        $this->expectExceptionMessage(sprintf(
            "Expected string '%s' to follow regex for snake_case with optional underscore (_) at beginning, '/^_?([a-z][a-z0-9]*)((\d)|(_[a-z0-9]+))*([a-z])?$/'",
            $indexName
        ));
        $this->expectException(InvalidArgumentException::class);
        new IndexName($indexName);
    }

    public function testIsEqualTo(): void
    {
        $indexNameA = new IndexName('some_index_name');
        $indexNameB = new IndexName('some_index_name');
        $indexNameC = new IndexName('other_index_name');
        $this->assertTrue($indexNameA->isEqualTo($indexNameB));
        $this->assertTrue($indexNameB->isEqualTo($indexNameA));
        $this->assertFalse($indexNameA->isEqualTo($indexNameC));
        $this->assertFalse($indexNameC->isEqualTo($indexNameA));
        $this->assertFalse($indexNameA->isEqualTo('something else'));
    }
}

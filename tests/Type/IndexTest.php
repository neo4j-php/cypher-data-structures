<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use Syndesi\CypherDataStructures\Type\Index;
use Syndesi\CypherDataStructures\Type\IndexName;
use Syndesi\CypherDataStructures\Type\IndexType;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\RelationType;

class IndexTest extends TestCase
{
    public function testIndexName(): void
    {
        $constraint = new Index();
        $this->assertNull($constraint->getIndexName());
        $constraint->setIndexName(new IndexName('some_name'));
        $this->assertSame('some_name', $constraint->getIndexName()->getIndexName());
        $constraint->setIndexName(null);
        $this->assertNull($constraint->getIndexName());
    }

    public function testIndexType(): void
    {
        $constraint = new Index();
        $this->assertNull($constraint->getIndexType());
        $constraint->setIndexType(IndexType::BTREE);
        $this->assertSame(IndexType::BTREE, $constraint->getIndexType());
        $constraint->setIndexType(null);
        $this->assertNull($constraint->getIndexType());
    }

    public function testFor(): void
    {
        $constraint = new Index();
        $this->assertNull($constraint->getFor());
        $constraint->setFor(new NodeLabel('SomeNode'));
        $this->assertSame('SomeNode', (string) $constraint->getFor());
        $constraint->setFor(new RelationType('SOME_TYPE'));
        $this->assertSame('SOME_TYPE', (string) $constraint->getFor());
        $constraint->setFor(null);
        $this->assertNull($constraint->getFor());
    }

    public function testOptions(): void
    {
        $constraint = new Index();
        $this->assertCount(0, $constraint->getOptions());
        $constraint->setOptions(['some' => 'options']);
        $this->assertCount(1, $constraint->getOptions());
        $this->assertArrayHasKey('some', $constraint->getOptions());
        $constraint->setOptions([]);
        $this->assertCount(0, $constraint->getOptions());
    }
}

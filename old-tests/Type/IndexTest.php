<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Tests\Type;

use PHPUnit\Framework\TestCase;
use stdClass;
use Syndesi\CypherDataStructures\Type\Index;
use Syndesi\CypherDataStructures\Type\IndexName;
use Syndesi\CypherDataStructures\Type\IndexType;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\PropertyName;
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

    public function testToString(): void
    {
        $index = new Index();
        $index->setIndexName(new IndexName('index'));
        $index->setFor(new NodeLabel('Node'));
        $index->setIndexType(IndexType::BTREE);
        $index->addProperty(new PropertyName('id'));
        $this->assertSame('BTREE INDEX index FOR (element:Node) ON (element.id)', (string) $index);
    }

    public function testIsEqualTo(): void
    {
        $indexA = new Index();
        $indexA->setIndexName(new IndexName('index_a'));
        $indexA->setFor(new NodeLabel('NodeA'));
        $indexA->addProperty(new PropertyName('id'));
        $indexA->setIndexType(IndexType::BTREE);

        $indexB = new Index();
        $indexB->setIndexName(new IndexName('index_a'));
        $indexB->setFor(new NodeLabel('NodeA'));
        $indexB->addProperty(new PropertyName('id'));
        $indexB->setIndexType(IndexType::BTREE);

        $indexC = new Index();
        $indexC->setIndexName(new IndexName('index_a'));
        $indexC->setFor(new RelationType('RELATION_A'));
        $indexC->addProperty(new PropertyName('id'));
        $indexC->setIndexType(IndexType::BTREE);

        $this->assertTrue($indexA->isEqualTo($indexB));
        $this->assertTrue($indexB->isEqualTo($indexA));
        $this->assertFalse($indexA->isEqualTo($indexC));
        $this->assertFalse($indexC->isEqualTo($indexA));
        $this->assertFalse($indexA->isEqualTo(new stdClass()));
        $this->assertFalse($indexA->isEqualTo('something'));
    }
}

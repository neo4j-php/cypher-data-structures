<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;
use Syndesi\CypherDataStructures\Type\IndexType;

interface IndexInterface extends Stringable, IsEqualToInterface, HasPropertiesInterface, HasOptionsInterface
{
    public function getIndexName(): ?IndexNameInterface;

    public function setIndexName(?IndexNameInterface $indexName): self;

    public function getIndexType(): ?IndexType;

    public function setIndexType(?IndexType $indexType): self;

    public function getFor(): NodeLabelInterface|RelationTypeInterface|null;

    public function setFor(NodeLabelInterface|RelationTypeInterface|null $for): self;
}

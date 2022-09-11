<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\IndexInterface;
use Syndesi\CypherDataStructures\Contract\IndexNameInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\RelationTypeInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Trait\OptionsTrait;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;

class Index implements IndexInterface
{
    use PropertiesTrait;
    use OptionsTrait;

    private ?IndexNameInterface $indexName = null;

    private ?IndexType $indexType = null;

    private NodeLabelInterface|RelationTypeInterface|null $for = null;

    public function __construct(
    ) {
        $this->initPropertiesTrait();
        $this->initOptionsTrait();
    }

    public function getIndexName(): ?IndexNameInterface
    {
        return $this->indexName;
    }

    public function setIndexName(?IndexNameInterface $indexName): self
    {
        $this->indexName = $indexName;

        return $this;
    }

    public function getIndexType(): ?IndexType
    {
        return $this->indexType;
    }

    public function setIndexType(?IndexType $indexType): self
    {
        $this->indexType = $indexType;

        return $this;
    }

    public function getFor(): NodeLabelInterface|RelationTypeInterface|null
    {
        return $this->for;
    }

    public function setFor(NodeLabelInterface|RelationTypeInterface|null $for): self
    {
        $this->for = $for;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __toString()
    {
        return ToCypherHelper::indexToCypherString($this);
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof IndexInterface)) {
            return false;
        }

        return ToCypherHelper::indexToCypherString($this) === ToCypherHelper::indexToCypherString($element);
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\IndexInterface;
use Syndesi\CypherDataStructures\Contract\IndexNameInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\RelationTypeInterface;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;

class Index implements IndexInterface
{
    use PropertiesTrait;

    private ?IndexNameInterface $constraintName = null;

    private ?IndexType $constraintType = null;

    private NodeLabelInterface|RelationTypeInterface|null $for = null;

    /**
     * @var array<string, mixed>
     */
    private array $options = [];

    public function __construct(
    ) {
        $this->initPropertiesTrait();
    }

    public function getIndexName(): ?IndexNameInterface
    {
        return $this->constraintName;
    }

    public function setIndexName(?IndexNameInterface $constraintName): self
    {
        $this->constraintName = $constraintName;

        return $this;
    }

    public function getIndexType(): ?IndexType
    {
        return $this->constraintType;
    }

    public function setIndexType(?IndexType $constraintType): self
    {
        $this->constraintType = $constraintType;

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

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function __toString()
    {
//        return ToCypherHelper::nodeToCypherString($this) ?? '()';
        return '';
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof IndexInterface)) {
            return false;
        }

//        return ToCypherHelper::nodeToIdentifyingCypherString($this) === ToCypherHelper::nodeToIdentifyingCypherString($element);
        return false;
    }
}

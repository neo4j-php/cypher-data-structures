<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;
use Syndesi\CypherDataStructures\Type\IndexType;

interface IndexInterface extends Stringable, IsEqualToInterface, HasPropertiesInterface
{
    public function getIndexName(): ?IndexNameInterface;

    public function setIndexName(?IndexNameInterface $constraintName): self;

    public function getIndexType(): ?IndexType;

    public function setIndexType(?IndexType $constraintType): self;

    public function getFor(): NodeLabelInterface|RelationTypeInterface|null;

    public function setFor(NodeLabelInterface|RelationTypeInterface|null $for): self;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options): self;
}

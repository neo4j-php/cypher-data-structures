<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;
use Syndesi\CypherDataStructures\Type\ConstraintType;

interface ConstraintInterface extends Stringable, IsEqualToInterface, HasPropertiesInterface
{
    public function getConstraintName(): ?ConstraintNameInterface;

    public function setConstraintName(?ConstraintNameInterface $constraintName): self;

    public function getConstraintType(): ?ConstraintType;

    public function setConstraintType(?ConstraintType $constraintType): self;

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

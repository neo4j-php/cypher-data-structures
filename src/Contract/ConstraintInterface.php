<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;
use Syndesi\CypherDataStructures\Type\ConstraintType;

interface ConstraintInterface extends Stringable, IsEqualToInterface, HasPropertiesInterface, HasOptionsInterface
{
    public function getConstraintName(): ?ConstraintNameInterface;

    public function setConstraintName(?ConstraintNameInterface $constraintName): self;

    public function getConstraintType(): ?ConstraintType;

    public function setConstraintType(?ConstraintType $constraintType): self;

    public function getFor(): NodeLabelInterface|RelationTypeInterface|null;

    public function setFor(NodeLabelInterface|RelationTypeInterface|null $for): self;
}

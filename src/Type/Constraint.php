<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\ConstraintInterface;
use Syndesi\CypherDataStructures\Contract\ConstraintNameInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\RelationTypeInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Trait\OptionsTrait;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;

class Constraint implements ConstraintInterface
{
    use PropertiesTrait;
    use OptionsTrait;

    private ?ConstraintNameInterface $constraintName = null;

    private ?ConstraintType $constraintType = null;

    private NodeLabelInterface|RelationTypeInterface|null $for = null;

    public function __construct(
    ) {
        $this->initPropertiesTrait();
        $this->initOptionsTrait();
    }

    public function getConstraintName(): ?ConstraintNameInterface
    {
        return $this->constraintName;
    }

    public function setConstraintName(?ConstraintNameInterface $constraintName): self
    {
        $this->constraintName = $constraintName;

        return $this;
    }

    public function getConstraintType(): ?ConstraintType
    {
        return $this->constraintType;
    }

    public function setConstraintType(?ConstraintType $constraintType): self
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

    /**
     * @throws InvalidArgumentException
     */
    public function __toString()
    {
        return ToCypherHelper::constraintToCypherString($this);
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof ConstraintInterface)) {
            return false;
        }

        return ToCypherHelper::constraintToCypherString($this) === ToCypherHelper::constraintToCypherString($element);
    }
}

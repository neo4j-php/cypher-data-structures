<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\ConstraintInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Trait\OptionsTrait;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;

class Constraint implements ConstraintInterface
{
    use PropertiesTrait;
    use OptionsTrait;

    private ?string $name = null;

    private ?string $type = null;

    private ?string $for = null;

    public function __construct(
    ) {
        $this->initPropertiesTrait();
        $this->initOptionsTrait();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFor(): ?string
    {
        return $this->for;
    }

    public function setFor(?string $for): self
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

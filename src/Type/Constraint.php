<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\ConstraintInterface;
use Syndesi\CypherDataStructures\Trait\OptionsTrait;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;

abstract class Constraint implements ConstraintInterface
{
    use PropertiesTrait;
    use OptionsTrait;

    private ?string $name = null;

    private ?string $type = null;

    private ?string $for = null;

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
}

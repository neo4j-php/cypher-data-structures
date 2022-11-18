<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\IndexInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Trait\OptionsTrait;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;

class Index implements IndexInterface
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

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Trait\IdentifiersTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class Relation implements RelationInterface
{
    use IdentifiersTrait;

    private ?NodeInterface $startNode = null;

    private ?NodeInterface $endNode = null;

    private ?string $type = null;

    public function __toString()
    {
        return ToCypherHelper::relationToCypherString($this);
    }

    public function getStartNode(): ?NodeInterface
    {
        return $this->startNode;
    }

    public function setStartNode(?NodeInterface $node): self
    {
        $this->startNode = $node;

        return $this;
    }

    public function getEndNode(): ?NodeInterface
    {
        return $this->endNode;
    }

    public function setEndNode(?NodeInterface $node): self
    {
        $this->endNode = $node;

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

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof RelationInterface)) {
            return false;
        }

        return ToCypherHelper::relationToIdentifyingCypherString($this) === ToCypherHelper::relationToIdentifyingCypherString($element);
    }
}

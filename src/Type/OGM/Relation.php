<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type\OGM;

use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Contract\RelationTypeInterface;
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

    private ?RelationTypeInterface $relationType = null;

    public function __construct(
    ) {
        $this->initIdentifiersTrait();
    }

    public function __toString()
    {
        return ToCypherHelper::relationToCypherString($this);
    }

    public function getStartNode(): ?NodeInterface
    {
        return $this->startNode;
    }

    public function setStartNode(?NodeInterface $node): RelationInterface
    {
        $this->startNode = $node;

        return $this;
    }

    public function getEndNode(): ?NodeInterface
    {
        return $this->endNode;
    }

    public function setEndNode(?NodeInterface $node): RelationInterface
    {
        $this->endNode = $node;

        return $this;
    }

    public function getRelationType(): ?RelationTypeInterface
    {
        return $this->relationType;
    }

    public function setRelationType(?RelationTypeInterface $relationType): RelationInterface
    {
        $this->relationType = $relationType;

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

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToStringHelper;
use Syndesi\CypherDataStructures\Trait\IdentifiersTrait;

class Node implements NodeInterface
{
    use IdentifiersTrait;

    /**
     * @var array<string, null>
     */
    private array $labels = [];
    /**
     * @var RelationInterface[]
     */
    private array $relations = [];

    public function __toString()
    {
        return ToStringHelper::nodeToString($this);
    }

    public function addLabel(string $label): self
    {
        $this->labels[$label] = null;

        return $this;
    }

    public function addLabels(iterable $labels): self
    {
        foreach ($labels as $label) {
            $this->addLabel($label);
        }

        return $this;
    }

    public function hasLabel(string $label): bool
    {
        return array_key_exists($label, $this->labels);
    }

    public function getLabels(): array
    {
        return array_keys($this->labels);
    }

    public function removeLabel(string $label): self
    {
        unset($this->labels[$label]);

        return $this;
    }

    public function removeLabels(): self
    {
        $this->labels = [];

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addRelation(RelationInterface $relation): self
    {
        $startNode = $relation->getStartNode();
        $endNode = $relation->getEndNode();
        if (!$startNode) {
            throw new InvalidArgumentException("Start node must be set");
        }
        if (!$endNode) {
            throw new InvalidArgumentException("End node must be set");
        }

        $ownIdentifyingString = ToStringHelper::nodeToString($this, true);
        if (ToStringHelper::nodeToString($startNode, true) !== $ownIdentifyingString &&
            ToStringHelper::nodeToString($endNode, true) !== $ownIdentifyingString
        ) {
            throw new InvalidArgumentException("Adding a relation to a node requires that either the start node or the end node must be the same as the node itself.");
        }

        $this->relations[] = $relation;

        return $this;
    }

    /**
     * @param iterable<RelationInterface> $relations
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function addRelations(iterable $relations): self
    {
        foreach ($relations as $relation) {
            $this->addRelation($relation);
        }

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hasRelation(RelationInterface $relation): bool
    {
        $relationIdentifyingString = ToStringHelper::relationToString($relation, identifying: true);
        foreach ($this->relations as $tmpRelation) {
            if (ToStringHelper::relationToString($tmpRelation, identifying: true) === $relationIdentifyingString) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return RelationInterface[]
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    public function removeRelation(RelationInterface $relation): self
    {
        $relationIdentifyingString = ToStringHelper::relationToString($relation, identifying: true);
        foreach ($this->relations as $key => $tmpRelation) {
            if (ToStringHelper::relationToString($tmpRelation, identifying: true) === $relationIdentifyingString) {
                unset($this->relations[$key]);
            }
        }

        return $this;
    }

    public function removeRelations(): self
    {
        $this->relations = [];

        return $this;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof NodeInterface)) {
            return false;
        }

        return ToStringHelper::nodeToString($this, true) === ToStringHelper::nodeToString($element, true);
    }
}

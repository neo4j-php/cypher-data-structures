<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelStorageInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Contract\WeakRelationInterface;
use Syndesi\CypherDataStructures\Contract\WeakRelationStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use Syndesi\CypherDataStructures\Trait\IdentifiersTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class Node implements NodeInterface
{
    use IdentifiersTrait;

    private NodeLabelStorageInterface $nodeLabelStorage;
    private WeakRelationStorageInterface $weakRelationStorage;

    public function __construct(
    ) {
        $this->nodeLabelStorage = new NodeLabelStorage();
        $this->weakRelationStorage = new WeakRelationStorage();
        $this->initIdentifiersTrait();
    }

    public function __toString()
    {
        return ToCypherHelper::nodeToCypherString($this) ?? '()';
    }

    // node label

    public function addNodeLabel(NodeLabelInterface $nodeLabel): self
    {
        $this->nodeLabelStorage->attach($nodeLabel);

        return $this;
    }

    public function addNodeLabels(NodeLabelStorageInterface $nodeLabelStorage): self
    {
        foreach ($nodeLabelStorage as $key) {
            $this->nodeLabelStorage->attach($key);
        }

        return $this;
    }

    public function hasNodeLabel(NodeLabelInterface $nodeLabel): bool
    {
        return $this->nodeLabelStorage->contains($nodeLabel);
    }

    public function getNodeLabels(): NodeLabelStorageInterface
    {
        return $this->nodeLabelStorage;
    }

    public function removeNodeLabel(NodeLabelInterface $nodeLabel): self
    {
        $this->nodeLabelStorage->detach($nodeLabel);

        return $this;
    }

    public function clearNodeLabels(): self
    {
        $this->nodeLabelStorage = new NodeLabelStorage();

        return $this;
    }

    // relations

    /**
     * @throws InvalidArgumentException
     */
    public function addRelation(WeakRelationInterface|RelationInterface $relation): self
    {
        $weakRelation = $relation;
        if ($weakRelation instanceof RelationInterface) {
            $weakRelation = WeakRelation::create($weakRelation);
        }
        if (null === $weakRelation->get()) {
            throw InvalidArgumentException::createForAlreadyNullReference(WeakRelationInterface::class);
        }
        /** @psalm-suppress PossiblyNullReference */
        if (null === $weakRelation->get()->getStartNode()) {
            throw InvalidArgumentException::createForTypeMismatch(NodeInterface::class, 'null');
        }
        /** @psalm-suppress PossiblyNullReference */
        if (null === $weakRelation->get()->getEndNode()) {
            throw InvalidArgumentException::createForTypeMismatch(NodeInterface::class, 'null');
        }
        $ownIdentifyingString = ToCypherHelper::nodeToIdentifyingCypherString($this);
        /** @psalm-suppress PossiblyNullReference */
        if (ToCypherHelper::nodeToIdentifyingCypherString($weakRelation->get()->getStartNode()) !== $ownIdentifyingString &&
            ToCypherHelper::nodeToIdentifyingCypherString($weakRelation->get()->getEndNode()) !== $ownIdentifyingString
        ) {
            throw new InvalidArgumentException("Adding a relation to a node requires that either the start node or the end node must be the same as the node itself.");
        }

        $this->weakRelationStorage->attach($weakRelation);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addRelations(WeakRelationStorageInterface $weakRelationStorage): self
    {
        foreach ($weakRelationStorage as $key) {
            $this->addRelation($key);
        }

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function hasRelation(WeakRelationInterface|RelationInterface $relation): bool
    {
        if ($relation instanceof RelationInterface) {
            $relation = WeakRelation::create($relation);
        }
        if (null === $relation->get()) {
            throw InvalidArgumentException::createForAlreadyNullReference(WeakRelationInterface::class);
        }

        return $this->weakRelationStorage->contains($relation);
    }

    public function getRelations(): WeakRelationStorageInterface
    {
        return $this->weakRelationStorage;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function removeRelation(WeakRelationInterface|RelationInterface $relation): self
    {
        if ($relation instanceof RelationInterface) {
            $relation = WeakRelation::create($relation);
        }
        if (null === $relation->get()) {
            throw InvalidArgumentException::createForAlreadyNullReference(WeakRelationInterface::class);
        }
        $this->weakRelationStorage->detach($relation);

        return $this;
    }

    public function clearRelations(): self
    {
        $this->weakRelationStorage = new WeakRelationStorage();

        return $this;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof NodeInterface)) {
            return false;
        }

        return ToCypherHelper::nodeToIdentifyingCypherString($this) === ToCypherHelper::nodeToIdentifyingCypherString($element);
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface NodeInterface extends \Stringable, IsEqualToInterface, HasIdentifiersInterface
{
    public function addLabel(string $label): self;

    /**
     * @param iterable<string> $labels
     */
    public function addLabels(iterable $labels): self;

    public function hasLabel(string $label): bool;

    /**
     * @return string[]
     */
    public function getLabels(): array;

    public function removeLabel(string $label): self;

    public function removeLabels(): self;

    public function addRelation(RelationInterface $relation): self;

    /**
     * @param iterable<RelationInterface> $relations
     */
    public function addRelations(iterable $relations): self;

    public function hasRelation(RelationInterface $relation): bool;

    /**
     * @return RelationInterface[]
     */
    public function getRelations(): array;

    public function removeRelation(RelationInterface $relation): self;

    public function removeRelations(): self;
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface NodeInterface extends Stringable, IsEqualToInterface, HasIdentifiersInterface
{
    // node label

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

    public function clearLabels(): self;

    // relations

    public function addRelation(RelationInterface $relation): self;

    /**
     * @param iterable<RelationInterface> $relations
     *
     * @return $this
     */
    public function addRelations(iterable $relations): self;

    public function hasRelation(RelationInterface $relation): bool;

    /**
     * @return RelationInterface[]
     */
    public function getRelations(): array;

    public function removeRelation(RelationInterface $relation): self;

    public function clearRelations(): self;
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface NodeInterface extends Stringable, IsEqualToInterface, HasIdentifiersInterface
{
    // node label

    public function addLabel(string $label): self;

    /**
     * @param string[] $labels
     */
    public function addLabels(iterable $labels): self;

    public function hasLabel(string $label): bool;

    /**
     * @return iterable<string>
     */
    public function getLabels(): iterable;

    public function removeLabel(string $label): self;

    public function clearLabels(): self;

    // relations

    public function addRelation(RelationInterface $relation): self;

    public function addRelations(WeakRelationStorageInterface $weakRelationStorage): self;

    public function hasRelation(RelationInterface $relation): bool;

    public function getRelations(): WeakRelationStorageInterface;

    public function removeRelation(RelationInterface $relation): self;

    public function clearRelations(): self;
}

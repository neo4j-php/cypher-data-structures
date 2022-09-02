<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface NodeInterface extends Stringable, IsEqualToInterface, HasPropertiesInterface
{
    // node label

    public function addNodeLabel(NodeLabelInterface $nodeLabel): self;

    public function addNodeLabels(NodeLabelStorageInterface $nodeLabelStorage): self;

    public function hasNodeLabel(NodeLabelInterface $nodeLabel): bool;

    public function getNodeLabels(): NodeLabelStorageInterface;

    public function removeNodeLabel(NodeLabelInterface $nodeLabel): self;

    public function clearNodeLabels(): self;

    // relations

    public function addRelation(RelationInterface $relation): self;

    public function addRelations(WeakRelationStorageInterface $weakRelationStorage): self;

    public function hasRelation(RelationInterface $relation): bool;

    public function getRelations(): WeakRelationStorageInterface;

    public function removeRelation(RelationInterface $relation): self;

    public function clearRelations(): self;
}

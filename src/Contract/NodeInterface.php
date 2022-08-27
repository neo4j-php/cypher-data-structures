<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface NodeInterface extends Stringable, IsEqualToInterface
{
    // node label

    public function addNodeLabel(NodeLabelInterface $nodeLabel): self;

    public function addNodeLabels(NodeLabelStorageInterface $nodeLabelStorage): self;

    public function hasNodeLabel(NodeLabelInterface $nodeLabel): bool;

    public function getNodeLabels(): NodeLabelStorageInterface;

    public function removeNodeLabel(NodeLabelInterface $nodeLabel): self;

    public function clearNodeLabels(): self;

    // node properties

    public function addProperty(PropertyNameInterface $propertyName, mixed $value): self;

    public function addProperties(PropertyStorageInterface $propertyStorage): self;

    public function hasProperty(PropertyNameInterface $propertyName): bool;

    public function getProperty(PropertyNameInterface $propertyName): mixed;

    public function getProperties(): PropertyStorageInterface;

    public function removeProperty(PropertyNameInterface $propertyName): self;

    public function clearProperties(): self;

    // node identifier

    public function addIdentifier(PropertyNameInterface $identifier): self;

    public function addIdentifiers(PropertyStorageInterface $identifies): self;

    public function hasIdentifier(PropertyNameInterface $identifier): bool;

    public function getIdentifier(PropertyNameInterface $identifier): mixed;

    public function getIdentifiers(): PropertyStorageInterface;

    public function removeIdentifier(PropertyNameInterface $identifier): self;

    public function clearIdentifier(): self;

    // relations

    // todo
}

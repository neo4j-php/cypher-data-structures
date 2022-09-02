<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\IsEqualToInterface;
use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelStorageInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Contract\WeakRelationStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Trait\PropertiesTrait;

class Node implements NodeInterface
{
    use PropertiesTrait;

    private NodeLabelStorageInterface $nodeLabelStorage;
    private WeakRelationStorageInterface $weakRelationStorage;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
    ) {
        $this->nodeLabelStorage = new NodeLabelStorage();
        $this->weakRelationStorage = new WeakRelationStorage();
        $this->initPropertiesTrait();
    }

    public function __toString()
    {
        // todo
        return '';
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

    public function addRelation(RelationInterface $relation): self
    {
        $this->weakRelationStorage->attach($relation);

        return $this;
    }

    public function addRelations(WeakRelationStorageInterface $weakRelationStorage): self
    {
        foreach ($weakRelationStorage as $key) {
            $this->weakRelationStorage->attach($key);
        }

        return $this;
    }

    public function hasRelation(RelationInterface $relation): bool
    {
        return $this->weakRelationStorage->contains($relation);
    }

    public function getRelations(): WeakRelationStorageInterface
    {
        return $this->weakRelationStorage;
    }

    public function removeRelation(RelationInterface $relation): self
    {
        $this->weakRelationStorage->detach($relation);

        return $this;
    }

    public function clearRelations(): self
    {
        $this->weakRelationStorage = new WeakRelationStorage();

        return $this;
    }

    // todo refactor with ToCypherHelper-methods?
    public function isEqualTo(mixed $element): bool
    {
        return false;
//        if (!($element instanceof NodeInterface)) {
//            return false;
//        }
//
//        // compare labels
//        if ($this->nodeLabelStorage->count() !== $element->getNodeLabels()->count()) {
//            return false;
//        }
//        foreach ($this->nodeLabelStorage as $key) {
//            if (!$element->hasNodeLabel($key)) {
//                return false;
//            }
//        }
//
//        // compare identifying properties
//        if ($this->identifierStorage->count() !== $element->getIdentifiers()->count()) {
//            return false;
//        }
//        foreach ($this->identifierStorage as $key) {
//            if (!$element->hasIdentifier($key)) {
//                return false;
//            }
//            $identifier = $this->getIdentifier($key);
//            $elementIdentifier = $element->getIdentifier($key);
//            if ($identifier instanceof IsEqualToInterface) {
//                if (!$identifier->isEqualTo($elementIdentifier)) {
//                    return false;
//                }
//            } else {
//                if ($identifier !== $elementIdentifier) {
//                    return false;
//                }
//            }
//        }
//
//        return true;
    }
}

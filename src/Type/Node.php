<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\IsEqualToInterface;
use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelStorageInterface;
use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class Node implements NodeInterface
{
    private NodeLabelStorageInterface $nodeLabelStorage;
    private PropertyStorageInterface $propertyStorage;
    private PropertyStorageInterface $identifierStorage;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
    ) {
        $this->nodeLabelStorage = new NodeLabelStorage();
        $this->propertyStorage = new PropertyStorage();
        $this->identifierStorage = new PropertyStorage();
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

    // node properties

    public function addProperty(PropertyNameInterface $propertyName, mixed $value): self
    {
        $this->propertyStorage->attach($propertyName, $value);

        return $this;
    }

    public function addProperties(PropertyStorageInterface $propertyStorage): self
    {
        foreach ($propertyStorage as $key) {
            $this->propertyStorage->attach($key, $propertyStorage->offsetGet($key));
        }

        return $this;
    }

    public function hasProperty(PropertyNameInterface $propertyName): bool
    {
        return $this->propertyStorage->contains($propertyName);
    }

    public function getProperty(PropertyNameInterface $propertyName): mixed
    {
        return $this->propertyStorage->offsetGet($propertyName);
    }

    public function getProperties(): PropertyStorageInterface
    {
        return $this->propertyStorage;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function removeProperty(PropertyNameInterface $propertyName): self
    {
        if ($this->identifierStorage->contains($propertyName)) {
            throw new InvalidArgumentException(sprintf("Unable to remove identifying property with name '%s' - remove identifier first", (string) $propertyName));
        }
        $this->propertyStorage->detach($propertyName);

        return $this;
    }

    public function clearProperties(): self
    {
        if ($this->identifierStorage->count() > 0) {
            throw new InvalidArgumentException("Unable to remove all properties because identifiers are still defined");
        }
        $this->propertyStorage = new PropertyStorage();

        return $this;
    }

    // node identifier

    public function addIdentifier(PropertyNameInterface $identifier): self
    {
        if (!$this->propertyStorage->contains($identifier)) {
            throw new InvalidArgumentException(sprintf("Unable to add identifier '%s' because there exists no property with the same name", (string) $identifier));
        }
        $this->identifierStorage->attach($identifier);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addIdentifiers(PropertyStorageInterface $identifies): self
    {
        foreach ($identifies as $identifier) {
            $this->addIdentifier($identifier);
        }

        return $this;
    }

    public function hasIdentifier(PropertyNameInterface $identifier): bool
    {
        return $this->identifierStorage->contains($identifier);
    }

    public function getIdentifier(PropertyNameInterface $identifier): mixed
    {
        return $this->propertyStorage->offsetGet($identifier);
    }

    public function getIdentifiers(): PropertyStorageInterface
    {
        return $this->identifierStorage;
    }

    public function removeIdentifier(PropertyNameInterface $identifier): self
    {
        $this->identifierStorage->detach($identifier);

        return $this;
    }

    public function clearIdentifier(): self
    {
        $this->identifierStorage = new PropertyStorage();

        return $this;
    }

    // todo refactor with ToCypherHelper-methods?
    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof NodeInterface)) {
            return false;
        }

        // compare labels
        if ($this->nodeLabelStorage->count() !== $element->getNodeLabels()->count()) {
            return false;
        }
        foreach ($this->nodeLabelStorage as $key) {
            if (!$element->hasNodeLabel($key)) {
                return false;
            }
        }

        // compare identifying properties
        if ($this->identifierStorage->count() !== $element->getIdentifiers()->count()) {
            return false;
        }
        foreach ($this->identifierStorage as $key) {
            if (!$element->hasIdentifier($key)) {
                return false;
            }
            $identifier = $this->getIdentifier($key);
            $elementIdentifier = $element->getIdentifier($key);
            if ($identifier instanceof IsEqualToInterface) {
                if (!$identifier->isEqualTo($elementIdentifier)) {
                    return false;
                }
            } else {
                if ($identifier !== $elementIdentifier) {
                    return false;
                }
            }
        }

        return true;
    }
}

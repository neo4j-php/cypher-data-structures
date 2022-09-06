<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Trait;

use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

trait IdentifiersTrait
{
    use PropertiesTrait;

    private PropertyStorageInterface $identifierStorage;

    private function initIdentifiersTrait(): void
    {
        $this->initPropertiesTrait();
        $this->identifierStorage = new PropertyStorage();
    }

    /**
     * @throws InvalidArgumentException
     */
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

    public function getIdentifiersWithPropertyValues(): PropertyStorageInterface
    {
        $identifierStorage = new PropertyStorage();
        foreach ($this->identifierStorage as $key) {
            $identifierStorage->attach($key, $this->propertyStorage->offsetGet($key));
        }

        return $identifierStorage;
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

    // overwrite methods from PropertiesTrait

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

    /**
     * @throws InvalidArgumentException
     */
    public function clearProperties(): self
    {
        if ($this->identifierStorage->count() > 0) {
            throw new InvalidArgumentException("Unable to remove all properties because identifiers are still defined");
        }
        $this->propertyStorage = new PropertyStorage();

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Trait;

use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

trait PropertiesTrait
{
    private PropertyStorageInterface $propertyStorage;
    private PropertyStorageInterface $identifierStorage;

    private function initPropertiesTrait(): void
    {
        $this->propertyStorage = new PropertyStorage();
        $this->identifierStorage = new PropertyStorage();
    }

    // properties

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

    // identifier

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
}

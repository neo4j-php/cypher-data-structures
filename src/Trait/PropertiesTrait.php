<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Trait;

use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Type\PropertyStorage;

trait PropertiesTrait
{
    private PropertyStorageInterface $propertyStorage;

    private function initPropertiesTrait(): void
    {
        $this->propertyStorage = new PropertyStorage();
    }

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

    public function removeProperty(PropertyNameInterface $propertyName): self
    {
        $this->propertyStorage->detach($propertyName);

        return $this;
    }

    public function clearProperties(): self
    {
        $this->propertyStorage = new PropertyStorage();

        return $this;
    }
}

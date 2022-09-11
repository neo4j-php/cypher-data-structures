<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface HasPropertiesInterface
{
    public function addProperty(PropertyNameInterface $propertyName, mixed $value = null): self;

    public function addProperties(PropertyStorageInterface $propertyStorage): self;

    public function hasProperty(PropertyNameInterface $propertyName): bool;

    public function getProperty(PropertyNameInterface $propertyName): mixed;

    public function getProperties(): PropertyStorageInterface;

    public function removeProperty(PropertyNameInterface $propertyName): self;

    public function clearProperties(): self;
}

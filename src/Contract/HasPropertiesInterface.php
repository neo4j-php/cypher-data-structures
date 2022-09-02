<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface HasPropertiesInterface
{
    // properties

    public function addProperty(PropertyNameInterface $propertyName, mixed $value): self;

    public function addProperties(PropertyStorageInterface $propertyStorage): self;

    public function hasProperty(PropertyNameInterface $propertyName): bool;

    public function getProperty(PropertyNameInterface $propertyName): mixed;

    public function getProperties(): PropertyStorageInterface;

    public function removeProperty(PropertyNameInterface $propertyName): self;

    public function clearProperties(): self;

    // identifier

    public function addIdentifier(PropertyNameInterface $identifier): self;

    public function addIdentifiers(PropertyStorageInterface $identifies): self;

    public function hasIdentifier(PropertyNameInterface $identifier): bool;

    public function getIdentifier(PropertyNameInterface $identifier): mixed;

    // todo return list of identifiers? return list of identifiers + current values?
    public function getIdentifiers(): PropertyStorageInterface;

    public function removeIdentifier(PropertyNameInterface $identifier): self;

    public function clearIdentifier(): self;
}

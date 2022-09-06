<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface HasIdentifiersInterface extends HasPropertiesInterface
{
    public function addIdentifier(PropertyNameInterface $identifier): self;

    public function addIdentifiers(PropertyStorageInterface $identifies): self;

    public function hasIdentifier(PropertyNameInterface $identifier): bool;

    public function getIdentifier(PropertyNameInterface $identifier): mixed;

    public function getIdentifiers(): PropertyStorageInterface;

    public function getIdentifiersWithPropertyValues(): PropertyStorageInterface;

    public function removeIdentifier(PropertyNameInterface $identifier): self;

    public function clearIdentifier(): self;
}

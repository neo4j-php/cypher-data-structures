<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface HasIdentifiersInterface extends HasPropertiesInterface
{
    public function addIdentifier(string $identifier): self;

    /**
     * @param string[] $identifiers
     */
    public function addIdentifiers(iterable $identifiers): self;

    public function hasIdentifier(string $identifier): bool;

    public function getIdentifier(string $identifier): mixed;

    /**
     * @return iterable<string>
     */
    public function getIdentifiers(): iterable;

    /**
     * @return iterable<string, mixed>
     */
    public function getIdentifiersWithPropertyValues(): iterable;

    public function removeIdentifier(string $identifier): self;

    public function clearIdentifier(): self;
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface HasIdentifiersInterface extends HasPropertiesInterface
{
    public function addIdentifier(string $identifier): self;

    /**
     * @param iterable<string> $identifiers
     */
    public function addIdentifiers(iterable $identifiers): self;

    public function hasIdentifier(string $identifier): bool;

    public function getIdentifier(string $identifier): mixed;

    /**
     * @return array<string, mixed>
     */
    public function getIdentifiers(): array;

    public function removeIdentifier(string $identifier): self;

    public function removeIdentifiers(): self;
}

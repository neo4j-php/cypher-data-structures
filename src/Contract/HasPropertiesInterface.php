<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface HasPropertiesInterface
{
    public function addProperty(string $name, mixed $value = null): self;

    /**
     * @param iterable<string, mixed> $properties
     */
    public function addProperties(iterable $properties): self;

    public function hasProperty(string $name): bool;

    public function getProperty(string $name): mixed;

    /**
     * @return iterable<string, mixed>
     */
    public function getProperties(): iterable;

    public function removeProperty(string $name): self;

    public function clearProperties(): self;
}

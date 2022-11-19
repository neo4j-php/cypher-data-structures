<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface HasOptionsInterface
{
    public function addOption(string $name, mixed $value = null): self;

    /**
     * @param iterable<string, mixed> $options
     */
    public function addOptions(iterable $options): self;

    public function hasOption(string $name): bool;

    public function getOption(string $name): mixed;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    public function removeOption(string $name): self;

    public function clearOptions(): self;
}

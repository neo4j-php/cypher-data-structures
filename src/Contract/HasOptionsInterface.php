<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface HasOptionsInterface
{
    public function addOption(OptionNameInterface $optionName, mixed $value = null): self;

    public function addOptions(OptionStorageInterface $optionStorage): self;

    public function hasOption(OptionNameInterface $optionName): bool;

    public function getOption(OptionNameInterface $optionName): mixed;

    public function getOptions(): OptionStorageInterface;

    public function removeOption(OptionNameInterface $optionName): self;

    public function clearOptions(): self;
}

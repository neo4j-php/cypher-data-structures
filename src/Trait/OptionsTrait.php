<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Trait;

use Syndesi\CypherDataStructures\Contract\OptionNameInterface;
use Syndesi\CypherDataStructures\Contract\OptionStorageInterface;
use Syndesi\CypherDataStructures\Type\OptionStorage;

trait OptionsTrait
{
    private OptionStorageInterface $optionStorage;

    private function initOptionsTrait(): void
    {
        $this->optionStorage = new OptionStorage();
    }

    public function addOption(OptionNameInterface $optionName, mixed $value = null): self
    {
        $this->optionStorage->attach($optionName, $value);

        return $this;
    }

    public function addOptions(OptionStorageInterface $optionStorage): self
    {
        foreach ($optionStorage as $key) {
            $this->optionStorage->attach($key, $optionStorage->offsetGet($key));
        }

        return $this;
    }

    public function hasOption(OptionNameInterface $optionName): bool
    {
        return $this->optionStorage->contains($optionName);
    }

    public function getOption(OptionNameInterface $optionName): mixed
    {
        return $this->optionStorage->offsetGet($optionName);
    }

    public function getOptions(): OptionStorageInterface
    {
        return $this->optionStorage;
    }

    public function removeOption(OptionNameInterface $optionName): self
    {
        $this->optionStorage->detach($optionName);

        return $this;
    }

    public function clearOptions(): self
    {
        $this->optionStorage = new OptionStorage();

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Trait;

trait OptionsTrait
{
    /**
     * @var array<string, mixed>
     */
    private array $options = [];

    public function addOption(string $name, mixed $value = null): self
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function addOptions(iterable $options): self
    {
        foreach ($options as $name => $value) {
            $this->addOption($name, $value);
        }

        return $this;
    }

    public function hasOption(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }

    public function getOption(string $name): mixed
    {
        return $this->options[$name];
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function removeOption(string $name): self
    {
        unset($this->options[$name]);

        return $this;
    }

    public function removeOptions(): self
    {
        $this->options = [];

        return $this;
    }
}

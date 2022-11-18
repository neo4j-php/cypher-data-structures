<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Trait;

use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

trait IdentifiersTrait
{
    use PropertiesTrait;

    /**
     * @var string[]
     */
    private array $identifiers = [];

    /**
     * @throws InvalidArgumentException
     */
    public function addIdentifier(string $identifier): self
    {
        if (!$this->hasProperty($identifier)) {
            throw new InvalidArgumentException(sprintf("Unable to add identifier '%s' because there exists no property with the same name", $identifier));
        }
        $this->identifiers[$identifier] = null;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function addIdentifiers(iterable $identifies): self
    {
        foreach ($identifies as $identifier) {
            $this->addIdentifier($identifier);
        }

        return $this;
    }

    public function hasIdentifier(string $identifier): bool
    {
        return array_key_exists($identifier, $this->identifiers);
    }

    public function getIdentifier(string $identifier): mixed
    {
        return $this->properties[$identifier];
    }

    public function getIdentifiers(): iterable
    {
        return array_keys($this->identifiers);
    }

    public function getIdentifiersWithPropertyValues(): iterable
    {
        foreach ($this->identifiers as $name => $value) {
            yield $name => $this->properties[$name];
        }
    }

    public function removeIdentifier(string $identifier): self
    {
        unset($this->identifiers[$identifier]);

        return $this;
    }

    public function clearIdentifier(): self
    {
        $this->identifiers = [];

        return $this;
    }

    // overwrite methods from PropertiesTrait

    /**
     * @throws InvalidArgumentException
     */
    public function removeProperty(string $name): self
    {
        if ($this->hasIdentifier($name)) {
            throw new InvalidArgumentException(sprintf("Unable to remove identifying property with name '%s' - remove identifier first", $name));
        }
        unset($this->properties[$name]);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function clearProperties(): self
    {
        if (count($this->identifiers) > 0) {
            throw new InvalidArgumentException("Unable to remove all properties because identifiers are still defined");
        }
        $this->properties = [];

        return $this;
    }
}

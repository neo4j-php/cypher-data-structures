<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class PropertyName implements PropertyNameInterface
{
    public const FORMAT_DESCRIPTION = 'camel case with optional underscore (_) at beginning';
    public const FORMAT = '/^_?[a-z]+((\d)|([A-Z0-9][a-z0-9]+))*([A-Z])?$/';

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $propertyName
    ) {
        if (!preg_match(self::FORMAT, $this->propertyName)) {
            throw InvalidArgumentException::createForRegexMismatch(self::FORMAT, self::FORMAT_DESCRIPTION, $this->propertyName);
        }
    }

    public function __toString()
    {
        return $this->getPropertyName();
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof PropertyNameInterface)) {
            return false;
        }

        return $this->getPropertyName() === $element->getPropertyName();
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Exception\LogicException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

class PropertyStorage extends SplObjectStorage implements PropertyStorageInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getHash(object $object): string
    {
        if (!($object instanceof PropertyNameInterface)) {
            throw InvalidArgumentException::createForTypeMismatch(PropertyNameInterface::class, $object::class);
        }

        return $object->getPropertyName();
    }

    /**
     * @throws LogicException
     */
    public function current(): PropertyNameInterface
    {
        $element = parent::current();
        if (!($element instanceof PropertyNameInterface)) {
            throw LogicException::createForInternalTypeMismatch(PropertyNameInterface::class, $element::class);
        }

        return $element;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof PropertyStorageInterface)) {
            return false;
        }

        return ToCypherHelper::propertyStorageToCypherPropertyString($this) === ToCypherHelper::propertyStorageToCypherPropertyString($element);
    }

    public function __toString(): string
    {
        return ToCypherHelper::propertyStorageToCypherPropertyString($this);
    }
}

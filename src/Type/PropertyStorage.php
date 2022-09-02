<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use LogicException;
use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

class PropertyStorage extends SplObjectStorage implements PropertyStorageInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getHash(object $object): string
    {
        if (!($object instanceof PropertyNameInterface)) {
            throw InvalidArgumentException::createForTypeMismatch(PropertyNameInterface::class, get_class($object));
        }

        return $object->getPropertyName();
    }

    public function current(): PropertyNameInterface
    {
        $element = parent::current();
        if (!($element instanceof PropertyNameInterface)) {
            throw new LogicException('Internal type missmatch');
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
}

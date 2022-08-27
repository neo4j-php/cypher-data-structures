<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\PropertyNameInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

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
        return parent::current();
    }
}

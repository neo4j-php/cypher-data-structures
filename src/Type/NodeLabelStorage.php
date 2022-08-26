<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class NodeLabelStorage extends SplObjectStorage
{
    /**
     * @throws InvalidArgumentException
     */
    public function getHash(object $object): string
    {
        if (!($object instanceof NodeLabelInterface)) {
            throw InvalidArgumentException::createForTypeMismatch(NodeLabelInterface::class, get_class($object));
        }

        return $object->getNodeLabel();
    }

    public function current(): NodeLabelInterface
    {
        return parent::current();
    }
}

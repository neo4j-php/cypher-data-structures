<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

class NodeLabelStorage extends SplObjectStorage implements NodeLabelStorageInterface
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

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof NodeLabelStorageInterface)) {
            return false;
        }

        return ToCypherHelper::nodeLabelStorageToCypherLabelString($this) === ToCypherHelper::nodeLabelStorageToCypherLabelString($element);
    }
}

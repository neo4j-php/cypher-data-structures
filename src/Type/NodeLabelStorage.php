<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Exception\LogicException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

class NodeLabelStorage extends SplObjectStorage implements NodeLabelStorageInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getHash(object $object): string
    {
        if (!($object instanceof NodeLabelInterface)) {
            throw InvalidArgumentException::createForTypeMismatch(NodeLabelInterface::class, $object::class);
        }

        return $object->getNodeLabel();
    }

    /**
     * @throws LogicException
     */
    public function current(): NodeLabelInterface
    {
        $element = parent::current();
        if (!($element instanceof NodeLabelInterface)) {
            throw LogicException::createForInternalTypeMismatch(NodeLabelInterface::class, $element::class);
        }

        return $element;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof NodeLabelStorageInterface)) {
            return false;
        }

        return ToCypherHelper::nodeLabelStorageToCypherLabelString($this) === ToCypherHelper::nodeLabelStorageToCypherLabelString($element);
    }
}

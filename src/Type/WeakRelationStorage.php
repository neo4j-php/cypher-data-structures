<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use LogicException;
use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\WeakRelationInterface;
use Syndesi\CypherDataStructures\Contract\WeakRelationStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

class WeakRelationStorage extends SplObjectStorage implements WeakRelationStorageInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getHash(object $object): string
    {
        if (!($object instanceof WeakRelationInterface)) {
            throw InvalidArgumentException::createForTypeMismatch(WeakRelationInterface::class, get_class($object));
        }

        // todo change to unique identifying string
        return '';
    }

    public function current(): WeakRelationInterface
    {
        $element = parent::current();
        if (!($element instanceof WeakRelationInterface)) {
            // @codeCoverageIgnoreStart
            throw new LogicException('Internal type missmatch');
            // @codeCoverageIgnoreEnd
        }

        return $element;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof WeakRelationStorageInterface)) {
            return false;
        }

        // todo
//        return ToCypherHelper::nodeLabelStorageToCypherLabelString($this) === ToCypherHelper::nodeLabelStorageToCypherLabelString($element);
        return false;
    }
}

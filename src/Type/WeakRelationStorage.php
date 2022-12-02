<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\WeakRelationInterface;
use Syndesi\CypherDataStructures\Contract\WeakRelationStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Exception\LogicException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

class WeakRelationStorage extends SplObjectStorage implements WeakRelationStorageInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getHash(object $object): string
    {
        if (!($object instanceof WeakRelationInterface)) {
            throw InvalidArgumentException::createForTypeMismatch(WeakRelationInterface::class, $object::class);
        }
        if (null === $object->get()) {
            throw InvalidArgumentException::createForAlreadyNullReference(WeakRelationInterface::class);
        }

        /** @psalm-suppress PossiblyNullArgument */
        return ToCypherHelper::relationToIdentifyingCypherString($object->get());
    }

    /**
     * @throws LogicException
     */
    public function current(): WeakRelationInterface
    {
        $element = parent::current();
        if (!($element instanceof WeakRelationInterface)) {
            throw LogicException::createForInternalTypeMismatch(WeakRelationInterface::class, $element::class);
        }

        return $element;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof WeakRelationStorageInterface)) {
            return false;
        }

        foreach ($this as $key) {
            if (!$element->offsetExists($key)) {
                return false;
            }
        }

        return true;
    }
}

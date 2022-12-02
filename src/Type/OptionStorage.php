<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use SplObjectStorage;
use Syndesi\CypherDataStructures\Contract\OptionNameInterface;
use Syndesi\CypherDataStructures\Contract\OptionStorageInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Exception\LogicException;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

class OptionStorage extends SplObjectStorage implements OptionStorageInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getHash(object $object): string
    {
        if (!($object instanceof OptionNameInterface)) {
            throw InvalidArgumentException::createForTypeMismatch(OptionNameInterface::class, $object::class);
        }

        return $object->getOptionName();
    }

    /**
     * @throws LogicException
     */
    public function current(): OptionNameInterface
    {
        $element = parent::current();
        if (!($element instanceof OptionNameInterface)) {
            throw LogicException::createForInternalTypeMismatch(OptionNameInterface::class, $element::class);
        }

        return $element;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof OptionStorageInterface)) {
            return false;
        }

        return ToCypherHelper::optionStorageToCypherString($this) === ToCypherHelper::optionStorageToCypherString($element);
    }

    public function __toString(): string
    {
        return ToCypherHelper::optionStorageToCypherString($this);
    }
}

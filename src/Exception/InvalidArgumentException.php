<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Exception;

class InvalidArgumentException extends CypherDataStructureException
{
    public static function createForTypeMismatch(string $typeExpected, string $typeGot): self
    {
        return new InvalidArgumentException(sprintf(
            "Expected type '%s', got type '%s'",
            $typeExpected,
            $typeGot
        ));
    }

    public static function createForRegexMismatch(string $regex, string $description, string $valueGot): self
    {
        return new InvalidArgumentException(sprintf(
            "Expected string '%s' to follow regex for %s, '%s'",
            $valueGot,
            $description,
            $regex
        ));
    }
}

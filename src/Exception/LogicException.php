<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Exception;

class LogicException extends CypherDataStructureException
{
    public static function createForInternalTypeMismatch(string $typeExpected, string $typeGot): self
    {
        return new LogicException(sprintf(
            "Internal type mismatch, expected type '%s', got type '%s'",
            $typeExpected,
            $typeGot
        ));
    }
}

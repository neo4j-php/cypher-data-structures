<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\RelationIndexInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToStringHelper;

class RelationIndex extends Index implements RelationIndexInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function __toString()
    {
        return ToStringHelper::relationIndexToString($this);
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof RelationIndexInterface)) {
            return false;
        }

        return ToStringHelper::relationIndexToString($this) === ToStringHelper::relationIndexToString($element);
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\RelationConstraintInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToStringHelper;

class RelationConstraint extends Constraint implements RelationConstraintInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function __toString()
    {
        return ToStringHelper::relationConstraintToString($this);
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof RelationConstraintInterface)) {
            return false;
        }

        return ToStringHelper::relationConstraintToString($this) === ToStringHelper::relationConstraintToString($element);
    }
}

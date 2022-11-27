<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\NodeConstraintInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToStringHelper;

class NodeConstraint extends Constraint implements NodeConstraintInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function __toString()
    {
        return ToStringHelper::nodeConstraintToString($this);
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof NodeConstraintInterface)) {
            return false;
        }

        return ToStringHelper::nodeConstraintToString($this) === ToStringHelper::nodeConstraintToString($element);
    }
}

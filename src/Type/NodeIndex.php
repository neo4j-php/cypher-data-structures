<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\NodeIndexInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Helper\ToStringHelper;

class NodeIndex extends Index implements NodeIndexInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function __toString()
    {
        return ToStringHelper::nodeIndexToString($this);
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof NodeIndexInterface)) {
            return false;
        }

        return ToStringHelper::nodeIndexToString($this) === ToStringHelper::nodeIndexToString($element);
    }
}

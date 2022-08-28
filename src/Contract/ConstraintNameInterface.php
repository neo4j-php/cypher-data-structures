<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface ConstraintNameInterface extends Stringable, IsEqualToInterface
{
    public function getConstraintName(): string;
}

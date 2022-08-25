<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface PropertyNameInterface extends Stringable, IsEqualToInterface
{
    public function getPropertyName(): string;
}

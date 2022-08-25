<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface IsEqualToInterface
{
    public function isEqualTo(mixed $element): bool;
}

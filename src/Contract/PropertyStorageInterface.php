<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface PropertyStorageInterface
{
    public function current(): PropertyNameInterface;
}

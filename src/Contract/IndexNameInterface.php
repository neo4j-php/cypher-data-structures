<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface IndexNameInterface extends Stringable, IsEqualToInterface
{
    public function getIndexName(): string;
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface RelationTypeInterface extends Stringable, IsEqualToInterface
{
    public function getRelationType(): string;
}

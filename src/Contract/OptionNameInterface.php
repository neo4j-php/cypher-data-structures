<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface OptionNameInterface extends Stringable, IsEqualToInterface
{
    public function getOptionName(): string;
}

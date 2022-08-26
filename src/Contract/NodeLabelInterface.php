<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface NodeLabelInterface extends Stringable, IsEqualToInterface
{
    public function getNodeLabel(): string;
}

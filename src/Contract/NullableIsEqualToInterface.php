<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface NullableIsEqualToInterface
{
    public function isEqualTo(mixed $element): null|bool;
}

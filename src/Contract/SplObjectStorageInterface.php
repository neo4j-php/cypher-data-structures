<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use ArrayAccess;
use Countable;
use Iterator;
use Serializable;

interface SplObjectStorageInterface extends Countable, Iterator, Serializable, ArrayAccess
{
    // @phpstan-ignore-next-line
    public function attach(object $object, mixed $info = null);

    // @phpstan-ignore-next-line
    public function contains(object $object);

    // @phpstan-ignore-next-line
    public function detach(object $object);
}

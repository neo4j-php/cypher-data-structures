<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use ArrayAccess;
use Countable;
use Iterator;
use Serializable;

interface SplObjectStorageInterface extends Countable, Iterator, Serializable, ArrayAccess
{
    /**
     * @psalm-suppress MissingReturnType
     * @phpstan-ignore-next-line
     */
    public function attach(object $object, mixed $info = null);

    /**
     * @psalm-suppress MissingReturnType
     * @phpstan-ignore-next-line
     */
    public function contains(object $object);

    /**
     * @psalm-suppress MissingReturnType
     * @phpstan-ignore-next-line
     */
    public function detach(object $object);
}

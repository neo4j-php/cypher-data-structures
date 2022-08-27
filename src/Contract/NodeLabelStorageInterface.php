<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use ArrayAccess;
use Countable;
use Iterator;
use Serializable;

/**
 * Classes which implement this interface should extend from SplObjectStorage,
 * see https://www.php.net/manual/en/class.splobjectstorage.
 */
interface NodeLabelStorageInterface extends Countable, Iterator, Serializable, ArrayAccess, IsEqualToInterface
{
    public function current(): NodeLabelInterface;
}

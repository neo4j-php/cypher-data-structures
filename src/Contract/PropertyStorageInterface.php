<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

/**
 * Classes which implement this interface should extend from SplObjectStorage,
 * see https://www.php.net/manual/en/class.splobjectstorage.
 */
interface PropertyStorageInterface extends SplObjectStorageInterface, IsEqualToInterface
{
    public function current(): PropertyNameInterface;
}

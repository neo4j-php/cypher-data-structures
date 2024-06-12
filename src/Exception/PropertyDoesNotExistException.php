<?php

declare(strict_types=1);

/*
 * This file is part of the Neo4j PHP Client and Driver package.
 *
 * (c) Nagels <https://nagels.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Syndesi\CypherDataStructures\Exception;

use RuntimeException;

/**
 * Exception when accessing a property which does not exist.
 *
 * @psalm-immutable
 *
 * @psalm-suppress MutableDependency
 */
final class PropertyDoesNotExistException extends RuntimeException
{
}

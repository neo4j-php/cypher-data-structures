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

use function get_debug_type;

final class RuntimeTypeException extends RuntimeException
{
    public function __construct(mixed $value, string $type)
    {
        $actualType = get_debug_type($value);
        $message = sprintf('Cannot cast %s to type: %s', $actualType, $type);
        parent::__construct($message);
    }
}

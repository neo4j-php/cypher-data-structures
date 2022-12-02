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

namespace Syndesi\CypherDataStructures;

use Syndesi\CypherDataStructures\Type\OGM\Cartesian3DPoint;
use Syndesi\CypherDataStructures\Type\OGM\CartesianPoint;
use Syndesi\CypherDataStructures\Type\OGM\Date;
use Syndesi\CypherDataStructures\Type\OGM\DateTime;
use Syndesi\CypherDataStructures\Type\OGM\Duration;
use Syndesi\CypherDataStructures\Type\OGM\LocalDateTime;
use Syndesi\CypherDataStructures\Type\OGM\LocalTime;
use Syndesi\CypherDataStructures\Type\OGM\Node;
use Syndesi\CypherDataStructures\Type\OGM\Path;
use Syndesi\CypherDataStructures\Type\OGM\Relationship;
use Syndesi\CypherDataStructures\Type\OGM\Time;
use Syndesi\CypherDataStructures\Type\OGM\WGS843DPoint;
use Syndesi\CypherDataStructures\Type\OGM\WGS84Point;
use function is_a;
use function is_iterable;
use function is_numeric;
use function is_object;
use function is_scalar;
use Syndesi\CypherDataStructures\Type\OGM\CypherList;
use Syndesi\CypherDataStructures\Type\OGM\CypherMap;
use function method_exists;

/**
 * @psalm-type OGMTypes = string|int|float|bool|null|Date|DateTime|Duration|LocalDateTime|LocalTime|Time|CypherList|CypherMap|Node|Relationship|Path|Cartesian3DPoint|CartesianPoint|WGS84Point|WGS843DPoint
 */
final class TypeCaster
{
    /**
     * @param mixed $value
     *
     * @pure
     */
    public static function toString($value): ?string
    {
        if ($value === null || is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @pure
     */
    public static function toFloat($value): ?float
    {
        $value = self::toString($value);
        if (is_numeric($value)) {
            return (float) $value;
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @pure
     */
    public static function toInt($value): ?int
    {
        $value = self::toFloat($value);
        if ($value !== null) {
            return (int) $value;
        }

        return null;
    }

    /**
     * @return null
     *
     * @pure
     */
    public static function toNull()
    {
        return null;
    }

    /**
     * @param mixed $value
     *
     * @pure
     */
    public static function toBool($value): ?bool
    {
        $value = self::toInt($value);
        if ($value !== null) {
            return (bool) $value;
        }

        return null;
    }

    /**
     * @template T
     *
     * @param mixed           $value
     * @param class-string<T> $class
     *
     * @return T|null
     *
     * @pure
     */
    public static function toClass($value, string $class): ?object
    {
        if (is_a($value, $class)) {
            /** @var T */
            return $value;
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @return list<mixed>
     *
     * @psalm-external-mutation-free
     */
    public static function toArray($value): ?array
    {
        if (is_iterable($value)) {
            $tbr = [];
            /** @var mixed $x */
            foreach ($value as $x) {
                /** @var mixed */
                $tbr[] = $x;
            }

            return $tbr;
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @return CypherList<mixed>|null
     *
     * @pure
     */
    public static function toCypherList($value): ?CypherList
    {
        if (is_iterable($value)) {
            return CypherList::fromIterable($value);
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @return CypherMap<mixed>|null
     */
    public static function toCypherMap($value): ?CypherMap
    {
        if (is_iterable($value)) {
            return CypherMap::fromIterable($value);
        }

        return null;
    }
}

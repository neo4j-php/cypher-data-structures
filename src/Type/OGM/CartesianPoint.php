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

namespace Syndesi\CypherDataStructures\Type\OGM;

use Syndesi\CypherDataStructures\Contract\OGM\PointInterface;

/**
 * A cartesian point in two dimensional space.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/functions/spatial/#functions-point-cartesian-2d
 *
 * @psalm-immutable
 *
 * @psalm-import-type Crs from PointInterface
 */
final class CartesianPoint extends AbstractPoint implements PointInterface
{
    /** @var Crs */
    public const CRS = 'cartesian';
    public const SRID = 7203;

    public function getCrs(): string
    {
        return self::CRS;
    }

    public function getSrid(): int
    {
        return self::SRID;
    }
}

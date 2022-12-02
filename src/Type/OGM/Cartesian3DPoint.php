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

use Laudis\Neo4j\Contracts\PointInterface;

/**
 * A cartesian point in three dimensional space.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/functions/spatial/#functions-point-cartesian-3d
 *
 * @psalm-immutable
 *
 * @psalm-import-type Crs from \Laudis\Neo4j\Contracts\PointInterface
 */
final class Cartesian3DPoint extends Abstract3DPoint implements PointInterface
{
    public const SRID = 9157;
    public const CRS = 'cartesian-3d';

    public function getSrid(): int
    {
        return self::SRID;
    }

    public function getCrs(): string
    {
        return self::CRS;
    }
}

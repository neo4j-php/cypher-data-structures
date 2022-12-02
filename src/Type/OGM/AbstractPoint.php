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
use Syndesi\CypherDataStructures\Contract\PackstreamConvertible;

/**
 * A cartesian point in two dimensional space.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/functions/spatial/#functions-point-cartesian-2d
 *
 * @psalm-immutable
 *
 * @psalm-import-type Crs from PointInterface
 */
abstract class AbstractPoint extends AbstractPropertyObject implements PointInterface, PackstreamConvertible
{
    private float $x;
    private float $y;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    abstract public function getCrs(): string;

    abstract public function getSrid(): int;

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getProperties(): CypherMap
    {
        /** @psalm-suppress InvalidReturnStatement False positive */
        return new CypherMap($this);
    }

    /**
     * @psalm-suppress ImplementedReturnTypeMismatch False positive
     *
     * @return array{x: float, y: float, crs: Crs, srid: int}
     */
    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'crs' => $this->getCrs(),
            'srid' => $this->getSrid(),
        ];
    }

    public function getPackstreamMarker(): int
    {
        return 0x58;
    }
}

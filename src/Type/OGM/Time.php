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


/**
 * A time object represented in seconds since the unix epoch.
 *
 * @psalm-immutable
 *
 * @extends AbstractPropertyObject<float, float>
 */
final class Time extends AbstractPropertyObject
{
    public function __construct(private int $nanoSeconds, private int $tzOffsetSeconds)
    {
    }

    /**
     * @return array{nanoSeconds: int, tzOffsetSeconds: int}
     */
    public function toArray(): array
    {
        return ['nanoSeconds' => $this->nanoSeconds, 'tzOffsetSeconds' => $this->tzOffsetSeconds];
    }

    public function getTzOffsetSeconds(): int
    {
        return $this->tzOffsetSeconds;
    }

    public function getNanoSeconds(): int
    {
        return $this->nanoSeconds;
    }

    public function getProperties(): Dictionary
    {
        return new Dictionary($this);
    }

    public function getPackstreamMarker(): int
    {
        return 0x54;
    }
}

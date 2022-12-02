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

use DateTimeImmutable;
use Exception;
use UnexpectedValueException;

/**
 * A date represented by days since unix epoch.
 *
 * @psalm-immutable
 *
 * @extends AbstractPropertyObject<int, int>
 *
 * @psalm-suppress TypeDoesNotContainType
 */
final class Date extends AbstractPropertyObject
{
    private int $days;

    public function __construct(int $days)
    {
        $this->days = $days;
    }

    /**
     * The amount of days since unix epoch.
     */
    public function getDays(): int
    {
        return $this->days;
    }

    /**
     * Casts to an immutable date time.
     *
     * @throws Exception
     */
    public function toDateTime(): DateTimeImmutable
    {
        $dateTimeImmutable = (new DateTimeImmutable('@0'))->modify(sprintf('+%s days', $this->days));

        if ($dateTimeImmutable === false) {
            throw new UnexpectedValueException('Expected DateTimeImmutable');
        }

        return $dateTimeImmutable;
    }

    public function getProperties(): CypherMap
    {
        return new CypherMap($this);
    }

    public function toArray(): array
    {
        return ['days' => $this->days];
    }

    public function getPackstreamMarker(): int
    {
        return 0x44;
    }
}

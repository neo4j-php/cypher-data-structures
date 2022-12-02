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
use function sprintf;
use UnexpectedValueException;

/**
 * A date time represented in seconds and nanoseconds since the unix epoch.
 *
 * @psalm-immutable
 *
 * @extends AbstractPropertyObject<int, int>
 *
 * @psalm-suppress TypeDoesNotContainType
 */
final class LocalDateTime extends AbstractPropertyObject
{
    private int $seconds;
    private int $nanoseconds;

    public function __construct(int $seconds, int $nanoseconds)
    {
        $this->seconds = $seconds;
        $this->nanoseconds = $nanoseconds;
    }

    /**
     * The amount of seconds since the unix epoch.
     */
    public function getSeconds(): int
    {
        return $this->seconds;
    }

    /**
     * The amount of nanoseconds after the seconds have passed.
     */
    public function getNanoseconds(): int
    {
        return $this->nanoseconds;
    }

    /**
     * @throws Exception
     */
    public function toDateTime(): DateTimeImmutable
    {
        $dateTimeImmutable = (new DateTimeImmutable(sprintf('@%s', $this->getSeconds())))->modify(sprintf('+%s microseconds', $this->nanoseconds / 1000));

        if ($dateTimeImmutable === false) {
            throw new UnexpectedValueException('Expected DateTimeImmutable');
        }

        return $dateTimeImmutable;
    }

    /**
     * @return array{seconds: int, nanoseconds: int}
     */
    public function toArray(): array
    {
        return [
            'seconds' => $this->seconds,
            'nanoseconds' => $this->nanoseconds,
        ];
    }

    public function getProperties(): CypherMap
    {
        return new CypherMap($this);
    }
}

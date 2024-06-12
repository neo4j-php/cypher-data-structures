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
use DateTimeInterface;
use DateTimeZone;
use Exception;
use RuntimeException;
use function sprintf;

/**
 * A date represented by seconds and nanoseconds since unix epoch, enriched with a timezone offset in seconds.
 *
 * @psalm-immutable
 *
 * @extends AbstractPropertyObject<int, int>
 */
final class DateTime extends AbstractPropertyObject
{
    public function __construct(private int $seconds, private int $nanoseconds, private int $tzOffsetSeconds)
    {
    }

    /**
     * Returns the amount of seconds since unix epoch.
     */
    public function getSeconds(): int
    {
        return $this->seconds;
    }

    /**
     * Returns the amount of nanoseconds after the seconds have passed.
     */
    public function getNanoseconds(): int
    {
        return $this->nanoseconds;
    }

    /**
     * Returns the timezone offset in seconds.
     */
    public function getTimeZoneOffsetSeconds(): int
    {
        return $this->tzOffsetSeconds;
    }

    /**
     * Casts to an immutable date time.
     *
     * @throws Exception
     */
    public function toDateTime(): DateTimeImmutable
    {
        /** @psalm-suppress all */
        foreach (DateTimeZone::listAbbreviations() as $tz) {
            /** @psalm-suppress all */
            if ($tz[0]['offset'] === $this->getTimeZoneOffsetSeconds()) {
                return (new DateTimeImmutable(sprintf('@%s', $this->getSeconds())))
                    ->modify(sprintf('+%s microseconds', $this->nanoseconds / 1000))
                    ->setTimezone(new DateTimeZone($tz[0]['timezone_id']));
            }
        }

        $message = sprintf('Cannot find an timezone with %s seconds as offset.', $this->tzOffsetSeconds);
        throw new RuntimeException($message);
    }

    /**
     * @return array{seconds: int, nanoseconds: int, tzOffsetSeconds: int}
     */
    public function toArray(): array
    {
        return [
            'seconds' => $this->seconds,
            'nanoseconds' => $this->nanoseconds,
            'tzOffsetSeconds' => $this->tzOffsetSeconds,
        ];
    }

    public function getProperties(): Dictionary
    {
        return new Dictionary($this);
    }

    public function getPackstreamMarker(): int
    {
        return 0x49;
    }

    public static function fromDateTime(DateTimeInterface $dateTime): self
    {
        return new self(
            $dateTime->getOffset(),
            ((int) $dateTime->format('u') * 1000),
            $dateTime->getTimezone()->getOffset($dateTime)
        );
    }
}

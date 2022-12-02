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
use Syndesi\CypherDataStructures\Contract\DateTimeConvertible;

use function sprintf;

/**
 * A date represented by seconds and nanoseconds since unix epoch, enriched with a timezone identifier.
 *
 * @psalm-immutable
 *
 * @extends AbstractPropertyObject<int|string, int|string>
 *
 * @psalm-suppress TypeDoesNotContainType
 */
final class DateTimeZoneId extends AbstractPropertyObject implements DateTimeConvertible
{
    public function __construct(private int $seconds, private int $nanoseconds, private string $tzId)
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
     * Returns the timezone identifier.
     */
    public function getTimezoneIdentifier(): string
    {
        return $this->tzId;
    }

    /**
     * Casts to an immutable date time.
     *
     * @throws Exception
     */
    public function toDateTime(): DateTimeImmutable
    {
        $dateTimeImmutable = (new DateTimeImmutable(sprintf('@%s', $this->getSeconds())))
            ->modify(sprintf('+%s microseconds', $this->nanoseconds / 1000));

        if ($dateTimeImmutable === false) {
            throw new \UnexpectedValueException('Expected DateTimeImmutable');
        }

        return $dateTimeImmutable->setTimezone(new DateTimeZone($this->tzId));
    }

    /**
     * @return array{seconds: int, nanoseconds: int, tzId: string}
     */
    public function toArray(): array
    {
        return [
            'seconds' => $this->seconds,
            'nanoseconds' => $this->nanoseconds,
            'tzId' => $this->tzId,
        ];
    }

    /**
     * @return Dictionary<string|int>
     */
    public function getProperties(): Dictionary
    {
        return new Dictionary($this);
    }

    public function getPackstreamMarker(): int
    {
        return 0x69;
    }

    public static function fromDateTime(DateTimeInterface $dateTime): self
    {
        return new self(
            $dateTime->getOffset(),
            ((int) $dateTime->format('u') * 1000),
            $dateTime->getTimezone()->getName()
        );
    }
}

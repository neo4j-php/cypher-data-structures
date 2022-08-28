<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\ConstraintNameInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class ConstraintName implements ConstraintNameInterface
{
    public const FORMAT_DESCRIPTION = 'snake_case with optional underscore (_) at beginning';
    public const FORMAT = '/^_?([a-z][a-z0-9]*)((\d)|(_[a-z0-9]+))*([a-z])?$/';

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $constraintName
    ) {
        if (!preg_match(self::FORMAT, $this->constraintName)) {
            throw InvalidArgumentException::createForRegexMismatch(self::FORMAT, self::FORMAT_DESCRIPTION, $this->constraintName);
        }
    }

    public function __toString()
    {
        return $this->getConstraintName();
    }

    public function getConstraintName(): string
    {
        return $this->constraintName;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof ConstraintNameInterface)) {
            return false;
        }

        return $this->getConstraintName() === $element->getConstraintName();
    }
}

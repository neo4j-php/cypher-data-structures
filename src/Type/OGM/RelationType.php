<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type\OGM;

use Syndesi\CypherDataStructures\Contract\RelationTypeInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class RelationType implements RelationTypeInterface
{
    public const FORMAT_DESCRIPTION = 'SCREAMING_SNAKE_CASE with optional underscore (_) at beginning';
    public const FORMAT = '/^_?[A-Z]([A-Z0-9]+_)*[A-Z0-9]*$/';

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $relationType
    ) {
        if (!preg_match(self::FORMAT, $this->relationType)) {
            throw InvalidArgumentException::createForRegexMismatch(self::FORMAT, self::FORMAT_DESCRIPTION, $this->relationType);
        }
    }

    public function __toString()
    {
        return $this->getRelationType();
    }

    public function getRelationType(): string
    {
        return $this->relationType;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof RelationTypeInterface)) {
            return false;
        }

        return $this->getRelationType() === $element->getRelationType();
    }
}

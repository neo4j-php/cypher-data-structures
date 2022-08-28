<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\IndexNameInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class IndexName implements IndexNameInterface
{
    public const FORMAT_DESCRIPTION = 'snake_case with optional underscore (_) at beginning';
    public const FORMAT = '/^_?([a-z][a-z0-9]*)((\d)|(_[a-z0-9]+))*([a-z])?$/';

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $indexName
    ) {
        if (!preg_match(self::FORMAT, $this->indexName)) {
            throw InvalidArgumentException::createForRegexMismatch(self::FORMAT, self::FORMAT_DESCRIPTION, $this->indexName);
        }
    }

    public function __toString()
    {
        return $this->getIndexName();
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof IndexNameInterface)) {
            return false;
        }

        return $this->getIndexName() === $element->getIndexName();
    }
}

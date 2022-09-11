<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\OptionNameInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class OptionName implements OptionNameInterface
{
    public const FORMAT_DESCRIPTION = 'strings containing only a-z, A-Z, 0-9 and the characters ".", "_" and "-"';
    public const FORMAT = '/^[a-zA-Z0-9._-]*$/';

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $optionName
    ) {
        if (!preg_match(self::FORMAT, $this->optionName)) {
            throw InvalidArgumentException::createForRegexMismatch(self::FORMAT, self::FORMAT_DESCRIPTION, $this->optionName);
        }
    }

    public function __toString()
    {
        return $this->getOptionName();
    }

    public function getOptionName(): string
    {
        return $this->optionName;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof OptionNameInterface)) {
            return false;
        }

        return $this->getOptionName() === $element->getOptionName();
    }
}

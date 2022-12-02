<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type\OGM;

use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class NodeLabel implements NodeLabelInterface
{
    public const FORMAT_DESCRIPTION = 'Camel case with optional underscore (_) at beginning';
    public const FORMAT = '/^_?([A-Z][a-z0-9]+)((\d)|([A-Z0-9][a-z0-9]+))*([A-Z])?$/';

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private readonly string $nodeLabel
    ) {
        if (!preg_match(self::FORMAT, $this->nodeLabel)) {
            throw InvalidArgumentException::createForRegexMismatch(self::FORMAT, self::FORMAT_DESCRIPTION, $this->nodeLabel);
        }
    }

    public function __toString()
    {
        return $this->getNodeLabel();
    }

    public function getNodeLabel(): string
    {
        return $this->nodeLabel;
    }

    public function isEqualTo(mixed $element): bool
    {
        if (!($element instanceof NodeLabelInterface)) {
            return false;
        }

        return $this->getNodeLabel() === $element->getNodeLabel();
    }
}

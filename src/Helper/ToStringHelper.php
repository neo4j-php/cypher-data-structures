<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Helper;

use Syndesi\CypherDataStructures\Contract\NodeConstraintInterface;
use Syndesi\CypherDataStructures\Contract\NodeIndexInterface;
use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\RelationConstraintInterface;
use Syndesi\CypherDataStructures\Contract\RelationIndexInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;

class ToStringHelper
{
    public const NO_STRING_REPRESENTATION = '<no string representation>';

    public static function mustNameBeEscaped(string $string): bool
    {
        // if string starts with digit
        if (1 === preg_match('/^\d/', $string)) {
            return true;
        }
        // if string contains whitespace or dots
        if (1 === preg_match('/[\s.]/', $string)) {
            return true;
        }

        return false;
    }

    public static function mustLabelBeEscaped(string $string): bool
    {
        // if string starts with digit
        if (1 === preg_match('/^\d/', $string)) {
            return true;
        }
        // if string contains characters which are not alphanumeric or part of selective whitelisted characters
        if (!preg_match('/^[A-Za-z0-9_]*$/', $string)) {
            return true;
        }

        return false;
    }

    /**
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public static function escapeString(string $string, string $character = '\''): string
    {
        if (1 !== strlen($character)) {
            throw new InvalidArgumentException(sprintf("Escape character must be of length 1, got '%s'", $character));
        }

        $escapedString = preg_replace_callback(
            sprintf(
                "/\\\*%s/",
                $character
            ),
            function ($match) {
                $match = $match[0];
                if (0 == strlen($match) % 2) {
                    // odd number of escaping slashes + one single character
                    // => even length & character is already escaped
                    return $match;
                }

                return sprintf(
                    "\\%s",
                    $match
                );
            },
            $string
        );
        if (null === $escapedString) {
            // @codeCoverageIgnoreStart
            // @infection-ignore-all
            throw new \Exception(preg_last_error_msg());
            // @codeCoverageIgnoreEnd
        }

        return $escapedString;
    }

    /**
     * @param array<mixed, mixed> $array
     *
     * @see https://stackoverflow.com/a/173479
     */
    public static function isArrayAssociate(array $array): bool
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function valueToString(mixed $value): string
    {
        if (is_string($value)) {
            return sprintf("'%s'", self::escapeString($value));
        }
        if (is_numeric($value)) {
            return (string) $value;
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_null($value)) {
            return 'null';
        }
        if (is_array($value)) {
            $parts = [];
            if (self::isArrayAssociate($value)) {
                asort($value);
                foreach ($value as $name => $part) {
                    if (self::mustNameBeEscaped($name)) {
                        $parts[] = sprintf(
                            "`%s`: %s",
                            self::escapeString($name),
                            self::valueToString($part)
                        );
                    } else {
                        $parts[] = sprintf(
                            "%s: %s",
                            $name,
                            self::valueToString($part)
                        );
                    }
                }
            } else {
                foreach ($value as $part) {
                    $parts[] = self::valueToString($part);
                }
            }

            $parts = implode(', ', $parts);

            return sprintf("[%s]", $parts);
        }
        if (is_object($value)) {
            if ($value instanceof \Stringable) {
                return (string) $value;
            }
        }

        return self::NO_STRING_REPRESENTATION;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public static function propertiesToString(array $properties, bool $escapeAllNames = false): string
    {
        ksort($properties);
        $parts = [];
        foreach ($properties as $name => $value) {
            $value = self::valueToString($value);
            if ($escapeAllNames || self::mustNameBeEscaped($name)) {
                $parts[] = sprintf(
                    "`%s`: %s",
                    self::escapeString($name),
                    $value
                );
            } else {
                $parts[] = sprintf(
                    "%s: %s",
                    $name,
                    $value
                );
            }
        }

        return implode(', ', $parts);
    }

    /**
     * @param string[] $labels
     */
    public static function labelsToString(array $labels, bool $escapeAllLabels = false): string
    {
        sort($labels);
        $parts = [];
        foreach ($labels as $label) {
            if (self::mustLabelBeEscaped($label) || $escapeAllLabels) {
                $label = sprintf(
                    "`%s`",
                    self::escapeString($label, '`')
                );
            }
            $parts[] = sprintf(":%s", $label);
        }

        return implode($parts);
    }

    /**
     * Turns a node to its Cypher string variant.
     *
     * @note relations are not included.
     */
    public static function nodeToString(NodeInterface $node, bool $identifying = false): string
    {
        $parts = [];
        $parts[] = self::labelsToString($node->getLabels());
        $properties = $node->getProperties();
        if ($identifying) {
            $properties = $node->getIdentifiers();
        }
        $propertyString = self::propertiesToString($properties);
        if (strlen($propertyString) > 0) {
            $parts[] = sprintf("{%s}", $propertyString);
        }

        return sprintf(
            "(%s)",
            implode(' ', $parts)
        );
    }

    public static function relationToString(RelationInterface $relation, bool $identifying = false, bool $withNodes = true): string
    {
        $parts = [];
        if ($withNodes) {
            if (($startNode = $relation->getStartNode()) === null) {
                throw new InvalidArgumentException('Start node can not be null');
            }
            $parts[] = self::nodeToString($startNode, true);
            $parts[] = '-';
        }

        $relationParts = [];
        if ($type = $relation->getType()) {
            $relationParts[] = sprintf(':%s', $type);
        }
        $properties = $relation->getProperties();
        if ($identifying) {
            $properties = $relation->getIdentifiers();
        }
        $propertyString = self::propertiesToString($properties);
        if (strlen($propertyString) > 0) {
            $relationParts[] = sprintf("{%s}", $propertyString);
        }
        $parts[] = sprintf("[%s]", implode(' ', $relationParts));

        if ($withNodes) {
            if (($endNode = $relation->getEndNode()) === null) {
                throw new InvalidArgumentException('End node can not be null');
            }
            $parts[] = '->';
            $parts[] = self::nodeToString($endNode, true);
        }

        return implode('', $parts);
    }

    /**
     * @param array<string, mixed> $options
     */
    public static function optionsToString(array $options): string
    {
        return self::propertiesToString($options);
    }

    public static function nodeConstraintToString(NodeConstraintInterface $nodeConstraint): string
    {
        $parts = [];

        $parts[] = 'CONSTRAINT';

        if ($name = $nodeConstraint->getName()) {
            $parts[] = $name;
        }

        $parts[] = 'FOR';

        $for = $nodeConstraint->getFor();
        if (!$for) {
            throw new InvalidArgumentException('For can not be null');
        }
        $parts[] = sprintf("(node:%s)", $for);

        $parts[] = 'REQUIRE';

        $properties = array_keys($nodeConstraint->getProperties());
        sort($properties);
        $propertyCount = count($properties);
        if (0 === $propertyCount) {
            throw new InvalidArgumentException('At least one property is required');
        }
        if (1 === $propertyCount) {
            $parts[] = sprintf("node.%s", array_shift($properties));
        }
        if ($propertyCount > 1) {
            $propertyParts = [];
            foreach ($properties as $property) {
                $propertyParts[] = sprintf("node.%s", $property);
            }
            $parts[] = sprintf("(%s)", implode(', ', $propertyParts));
        }

        $parts[] = 'IS';

        $type = $nodeConstraint->getType();
        if (!$type) {
            throw new InvalidArgumentException('Type can not be null');
        }
        $parts[] = $type;

        $options = $nodeConstraint->getOptions();
        if (count($options) > 0) {
            $parts[] = 'OPTIONS';
            $parts[] = sprintf("{%s}", self::optionsToString($options));
        }

        return implode(' ', $parts);
    }

    public static function relationConstraintToString(RelationConstraintInterface $relationConstraint): string
    {
        $parts = [];

        $parts[] = 'CONSTRAINT';

        if ($name = $relationConstraint->getName()) {
            $parts[] = $name;
        }

        $parts[] = 'FOR';

        $for = $relationConstraint->getFor();
        if (!$for) {
            throw new InvalidArgumentException('For can not be null');
        }
        $parts[] = sprintf("()-[relation:%s]-()", $for);

        $parts[] = 'REQUIRE';

        $properties = array_keys($relationConstraint->getProperties());
        sort($properties);
        $propertyCount = count($properties);
        if (0 === $propertyCount) {
            throw new InvalidArgumentException('At least one property is required');
        }
        if (1 === $propertyCount) {
            $parts[] = sprintf("relation.%s", array_shift($properties));
        }
        if ($propertyCount > 1) {
            $propertyParts = [];
            foreach ($properties as $property) {
                $propertyParts[] = sprintf("relation.%s", $property);
            }
            $parts[] = sprintf("(%s)", implode(', ', $propertyParts));
        }

        $parts[] = 'IS';

        $type = $relationConstraint->getType();
        if (!$type) {
            throw new InvalidArgumentException('Type can not be null');
        }
        $parts[] = $type;

        $options = $relationConstraint->getOptions();
        if (count($options) > 0) {
            $parts[] = 'OPTIONS';
            $parts[] = sprintf("{%s}", self::optionsToString($options));
        }

        return implode(' ', $parts);
    }

    public static function nodeIndexToString(NodeIndexInterface $nodeIndex): string
    {
        $parts = [];

        $type = $nodeIndex->getType();
        if ($type) {
            $parts[] = $type;
        }

        $parts[] = 'INDEX';

        if ($name = $nodeIndex->getName()) {
            $parts[] = $name;
        }

        $parts[] = 'FOR';

        $for = $nodeIndex->getFor();
        if (!$for) {
            throw new InvalidArgumentException('For can not be null');
        }
        $parts[] = sprintf("(node:%s)", $for);

        $parts[] = 'ON';

        $properties = array_keys($nodeIndex->getProperties());
        sort($properties);
        $propertyCount = count($properties);
        if (0 === $propertyCount) {
            throw new InvalidArgumentException('At least one property is required');
        }
        $propertyParts = [];
        foreach ($properties as $property) {
            $propertyParts[] = sprintf("node.%s", $property);
        }
        $parts[] = sprintf("(%s)", implode(', ', $propertyParts));

        $options = $nodeIndex->getOptions();
        if (count($options) > 0) {
            $parts[] = 'OPTIONS';
            $parts[] = sprintf("{%s}", self::optionsToString($options));
        }

        return implode(' ', $parts);
    }

    public static function relationIndexToString(RelationIndexInterface $relationIndex): string
    {
        $parts = [];

        $type = $relationIndex->getType();
        if ($type) {
            $parts[] = $type;
        }

        $parts[] = 'INDEX';

        if ($name = $relationIndex->getName()) {
            $parts[] = $name;
        }

        $parts[] = 'FOR';

        $for = $relationIndex->getFor();
        if (!$for) {
            throw new InvalidArgumentException('For can not be null');
        }
        $parts[] = sprintf("()-[relation:%s]-()", $for);

        $parts[] = 'ON';

        $properties = array_keys($relationIndex->getProperties());
        sort($properties);
        $propertyCount = count($properties);
        if (0 === $propertyCount) {
            throw new InvalidArgumentException('At least one property is required');
        }
        $propertyParts = [];
        foreach ($properties as $property) {
            $propertyParts[] = sprintf("relation.%s", $property);
        }
        $parts[] = sprintf("(%s)", implode(', ', $propertyParts));

        $options = $relationIndex->getOptions();
        if (count($options) > 0) {
            $parts[] = 'OPTIONS';
            $parts[] = sprintf("{%s}", self::optionsToString($options));
        }

        return implode(' ', $parts);
    }
}

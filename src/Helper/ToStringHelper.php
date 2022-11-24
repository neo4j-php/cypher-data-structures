<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Helper;

use Exception;
use Stringable;
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
     * @throws Exception
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
            throw new Exception(preg_last_error_msg());
            // @codeCoverageIgnoreEnd
        }

        return $escapedString;
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
            asort($value);
            $parts = [];
            foreach ($value as $part) {
                $parts[] = self::valueToString($part);
            }
            $parts = implode(', ', $parts);

            return sprintf("[%s]", $parts);
        }
        if (is_object($value)) {
            if ($value instanceof Stringable) {
                return (string) $value;
            }
        }

        return self::NO_STRING_REPRESENTATION;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public static function propertyArrayToString(array $properties, bool $escapeAllNames = false): string
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
}

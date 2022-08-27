<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Helper;

use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;

class ToCypherHelper
{
    /**
     * This method transforms property storage to a Cypher property string with sorted keys.
     * Return example: {name: 'Andy', title: 'Developer'}.
     */
    public static function propertyStorageToCypherPropertyString(PropertyStorageInterface $propertyStorage): string
    {
        $properties = [];
        foreach ($propertyStorage as $key) {
            $properties[(string) $key] = (string) $propertyStorage->offsetGet($key);
        }
        ksort($properties);
        $propertyStringParts = [];
        foreach ($properties as $key => $value) {
            $propertyStringParts[] = sprintf(
                "%s: '%s'",
                $key,
                EscapeHelper::escapeCharacter("'", $value)
            );
        }

        return implode(', ', $propertyStringParts);
    }
}

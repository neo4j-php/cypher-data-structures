<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Helper;

use Syndesi\CypherDataStructures\Contract\NodeLabelStorageInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;

class ToCypherHelper
{
    /**
     * This method transforms property storage to a Cypher property string with sorted keys.
     * Return example: {name: 'Andy', title: 'Developer'}.
     */
    public static function propertyStorageToCypherPropertyString(PropertyStorageInterface $propertyStorage): string
    {
        $internalProperties = [];
        $publicProperties = [];
        foreach ($propertyStorage as $key) {
            $value = (string) $propertyStorage->offsetGet($key);
            $key = (string) $key;
            if (str_starts_with($key, '_')) {
                $internalProperties[$key] = $value;
            } else {
                $publicProperties[$key] = $value;
            }
        }
        ksort($internalProperties);
        ksort($publicProperties);
        $propertyStringParts = [];
        foreach (array_merge($internalProperties, $publicProperties) as $key => $value) {
            $propertyStringParts[] = sprintf(
                "%s: '%s'",
                $key,
                EscapeHelper::escapeCharacter("'", $value)
            );
        }

        return implode(', ', $propertyStringParts);
    }

    /**
     * This method transforms node label storage to a Cypher label string with sorted labels.
     * Return example: :_Internal:LabelA:LabelB:LabelC.
     */
    public static function nodeLabelStorageToCypherLabelString(NodeLabelStorageInterface $nodeLabelStorage): string
    {
        if (0 === $nodeLabelStorage->count()) {
            return '';
        }

        $internalNodeLabels = [];
        $publicNodeLabels = [];
        foreach ($nodeLabelStorage as $key) {
            $key = (string) $key;
            if (str_starts_with($key, '_')) {
                $internalNodeLabels[] = $key;
            } else {
                $publicNodeLabels[] = $key;
            }
        }
        sort($internalNodeLabels);
        sort($publicNodeLabels);

        return sprintf(
            ":%s",
            implode(':', array_merge($internalNodeLabels, $publicNodeLabels))
        );
    }
}

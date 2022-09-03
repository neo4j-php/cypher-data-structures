<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Helper;

use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelStorageInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;

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

    /**
     * This method transforms a node to a Cypher node string, optionally limiting included properties to identifying
     * ones.
     * Return example: (:Label {property: 'value'}).
     */
    public static function nodeToCypherString(?NodeInterface $node, bool $identifying = false): ?string
    {
        if (null === $node) {
            return null;
        }
        $parts = [];
        $cypherLabelString = self::nodeLabelStorageToCypherLabelString($node->getNodeLabels());
        if ('' !== $cypherLabelString) {
            $parts[] = $cypherLabelString;
        }
        $propertyStorage = $node->getProperties();
        if ($identifying) {
            $propertyStorage = $node->getIdentifiersWithPropertyValues();
        }
        $propertyString = self::propertyStorageToCypherPropertyString($propertyStorage);
        if ('' !== $propertyString) {
            $parts[] = '{'.$propertyString.'}';
        }

        return '('.implode(' ', $parts).')';
    }

    public static function nodeToIdentifyingCypherString(?NodeInterface $node): ?string
    {
        return self::nodeToCypherString($node, true);
    }

    public static function relationToCypherString(RelationInterface $relation, bool $identifying = false, bool $withNodes = true): string
    {
        $parts = [];
        if ($withNodes) {
            $parts[] = self::nodeToIdentifyingCypherString($relation->getStartNode()) ?? '()';
        }

        $relationParts = [];
        if ($relation->getRelationType()) {
            /** @psalm-suppress PossiblyNullReference */
            $relationParts[] = ':'.$relation->getRelationType()->getRelationType();
        }
        $propertyStorage = $relation->getProperties();
        if ($identifying) {
            $propertyStorage = $relation->getIdentifiersWithPropertyValues();
        }
        $propertyString = self::propertyStorageToCypherPropertyString($propertyStorage);
        if ('' !== $propertyString) {
            $relationParts[] = '{'.$propertyString.'}';
        }
        $relationParts = '['.implode(' ', $relationParts).']';
        if ($withNodes) {
            $relationParts = '-'.$relationParts.'->';
        }

        $parts[] = $relationParts;
        if ($withNodes) {
            $parts[] = self::nodeToIdentifyingCypherString($relation->getEndNode()) ?? '()';
        }

        return implode('', $parts);
    }

    public static function relationToIdentifyingCypherString(RelationInterface $relation, bool $withNodes = true): string
    {
        return self::relationToCypherString($relation, true, $withNodes);
    }
}

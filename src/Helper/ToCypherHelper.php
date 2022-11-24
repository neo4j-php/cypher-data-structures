<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Helper;

use Stringable;
use Syndesi\CypherDataStructures\Contract\ConstraintInterface;
use Syndesi\CypherDataStructures\Contract\IndexInterface;
use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\PropertyName;

class ToCypherHelper
{
    /**
     * @param array<string, mixed> $properties
     *
     * @throws InvalidArgumentException
     */
    public static function propertyArrayToCypherPropertyString(array $properties): string
    {
        $internalProperties = [];
        $publicProperties = [];
        foreach ($properties as $name => $value) {
            if (str_starts_with($name, '_')) {
                $internalProperties[$name] = $value;
            } else {
                $publicProperties[$name] = $value;
            }
        }
        ksort($internalProperties);
        ksort($publicProperties);
        $resultParts = [];
        foreach (array_merge($internalProperties, $publicProperties) as $name => $value) {
            $resultParts[] = sprintf(
                "%s: '%s'",
                $name,
                EscapeHelper::escapeCharacter("'", (string) $value)
            );
        }

        return implode(', ', $resultParts);
    }

    /**
     * @param string[] $labels
     */
    public static function nodeLabelsToCypherLabelString(array $labels): string
    {
        if (0 === count($labels)) {
            return '';
        }

        $internalLabels = [];
        $publicLabels = [];
        foreach ($labels as $name) {
            if (str_starts_with($name, '_')) {
                $internalLabels[] = $name;
            } else {
                $publicLabels[] = $name;
            }
        }
        sort($internalLabels);
        sort($publicLabels);

        return sprintf(
            ":%s",
            implode(':', array_merge($internalLabels, $publicLabels))
        );
    }

    /**
     * This method transforms a node to a Cypher node string, optionally limiting included properties to identifying
     * ones.
     * Return example: (:Label {property: 'value'}).
     */
    public static function nodeToCypherString(NodeInterface $node, bool $identifying = false, ?string $nodeVariable = null): string
    {
        $parts = [];
        $cypherLabelString = self::nodeLabelsToCypherLabelString($node->getLabels());
        if ('' !== $cypherLabelString) {
            $parts[] = $cypherLabelString;
        }
        $properties = $node->getProperties();
        if ($identifying) {
            $properties = $node->getIdentifiers();
        }
        $propertyString = self::propertyArrayToCypherPropertyString($properties);
        if ('' !== $propertyString) {
            $parts[] = '{'.$propertyString.'}';
        }
        if (!$nodeVariable) {
            $nodeVariable = '';
        }

        return '('.$nodeVariable.implode(' ', $parts).')';
    }

    public static function nodeToIdentifyingCypherString(NodeInterface $node, ?string $nodeVariable = null): string
    {
        return self::nodeToCypherString($node, true, $nodeVariable);
    }

    public static function relationToCypherString(RelationInterface $relation, bool $identifying = false, bool $withNodes = true, ?string $relationVariable = null): string
    {
        $parts = [];
        if ($withNodes) {
            $startNode = $relation->getStartNode();
            if (!$startNode) {
                throw new InvalidArgumentException("Start node can not be null");
            }
            $parts[] = self::nodeToIdentifyingCypherString($startNode);
        }

        $relationParts = [];
        $type = $relation->getType();
        if ($type) {
            if (!$relationVariable) {
                $relationVariable = '';
            }
            $relationParts[] = $relationVariable.':'.$type;
        }
        $properties = $relation->getProperties();
        if ($identifying) {
            $properties = $relation->getIdentifiers();
        }
        $propertyString = self::propertyArrayToCypherPropertyString($properties);
        if ('' !== $propertyString) {
            $relationParts[] = '{'.$propertyString.'}';
        }
        $relationParts = '['.implode(' ', $relationParts).']';
        if ($withNodes) {
            $relationParts = '-'.$relationParts.'->';
        }

        $parts[] = $relationParts;
        if ($withNodes) {
            $endNode = $relation->getEndNode();
            if (!$endNode) {
                throw new InvalidArgumentException("End node can not be null");
            }
            $parts[] = self::nodeToIdentifyingCypherString($endNode);
        }

        return implode('', $parts);
    }

    public static function relationToIdentifyingCypherString(RelationInterface $relation, bool $withNodes = true, ?string $relationVariable = null): string
    {
        return self::relationToCypherString($relation, true, $withNodes, $relationVariable);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function constraintToCypherString(ConstraintInterface $constraint): string
    {
        if (null === $constraint->getFor()) {
            throw InvalidArgumentException::createForTypeMismatch('string', 'null');
        }
        if (null === $constraint->getName()) {
            throw InvalidArgumentException::createForTypeMismatch('string', 'null');
        }
        if (null === $constraint->getType()) {
            throw InvalidArgumentException::createForTypeMismatch('string', 'null');
        }
        if (0 === count($constraint->getProperties())) {
            throw new InvalidArgumentException("At least one property is required");
        }

//        $for = $constraint->getFor();
//        if ($for instanceof NodeLabelInterface) {
//            $forIdentifier = sprintf(
//                "(element:%s)",
//                $for->getNodeLabel()
//            );
//        } else {
//            /**
//             * @psalm-suppress PossiblyNullReference
//             */
//            $forIdentifier = sprintf(
//                "()-[element:%s]-()",
//                $for->getType()
//            );
//        }
//
//        $properties = [];
//        /** @var PropertyName $propertyName */
//        foreach ($constraint->getProperties() as $propertyName) {
//            $properties[] = sprintf(
//                "element.%s",
//                $propertyName->getPropertyName()
//            );
//        }
//        $properties = implode(', ', $properties);
//
//        $optionsString = '';
//        if ($constraint->getOptions()->count() > 0) {
//            $optionsString = sprintf(
//                " OPTIONS %s",
//                self::optionArrayToCypherString($constraint->getOptions())
//            );
//        }
//
//        /**
//         * @psalm-suppress PossiblyNullReference
//         * @psalm-suppress PossiblyNullArgument
//         * @psalm-suppress PossiblyNullPropertyFetch
//         */
//        return sprintf(
//            "CONSTRAINT %s FOR %s REQUIRE (%s) IS %s%s",
//            $constraint->getConstraintName()->getConstraintName(),
//            $forIdentifier,
//            $properties,
//            $constraint->getConstraintType()->value,
//            $optionsString
//        );
        // todo
        return 'todo';
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function indexToCypherString(IndexInterface $index): string
    {
        if (null === $index->getFor()) {
            throw InvalidArgumentException::createForTypeMismatch('string', 'null');
        }
        if (null === $index->getName()) {
            throw InvalidArgumentException::createForTypeMismatch('string', 'null');
        }
        if (null === $index->getType()) {
            throw InvalidArgumentException::createForTypeMismatch('string', 'null');
        }

//        $for = $index->getFor();
//        if ($for instanceof NodeLabelInterface) {
//            $forIdentifier = sprintf(
//                "(element:%s)",
//                $for->getNodeLabel()
//            );
//        } else {
//            /**
//             * @psalm-suppress PossiblyNullReference
//             */
//            $forIdentifier = sprintf(
//                "()-[element:%s]-()",
//                $for->getType()
//            );
//        }
//
//        $properties = [];
//        /** @var PropertyName $propertyName */
//        foreach ($index->getProperties() as $propertyName) {
//            $properties[] = sprintf(
//                "element.%s",
//                $propertyName->getPropertyName()
//            );
//        }
//        $properties = implode(', ', $properties);
//
//        $optionsString = '';
//        if ($index->getOptions()->count() > 0) {
//            $optionsString = sprintf(
//                " OPTIONS %s",
//                self::optionArrayToCypherString($index->getOptions())
//            );
//        }
//
//        /**
//         * @psalm-suppress PossiblyNullReference
//         * @psalm-suppress PossiblyNullArgument
//         * @psalm-suppress PossiblyNullPropertyFetch
//         */
//        return sprintf(
//            "%s INDEX %s FOR %s ON (%s)%s",
//            $index->getIndexType()->value,
//            $index->getIndexName()->getIndexName(),
//            $forIdentifier,
//            $properties,
//            $optionsString
//        );
        // todo
        return 'todo';
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function valueToString(mixed $value): string
    {
        if (is_string($value)) {
            return sprintf("'%s'", EscapeHelper::escapeCharacter("'", $value));
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
            if (!($value instanceof Stringable)) {
                throw InvalidArgumentException::createForTypeMismatch(Stringable::class, get_class($value));
            }

            return (string) $value;
        }
        throw new InvalidArgumentException("Unable to cast value to string");
    }

    /**
     * @param array<string, mixed> $options
     *
     * @throws InvalidArgumentException
     */
    public static function optionArrayToCypherString(array $options): string
    {
        ksort($options);
        $returnParts = [];
        foreach ($options as $name => $value) {
            $returnParts[] = sprintf(
                "`%s`: %s",
                $name,
                $value
            );
        }

        $returnParts = implode(', ', $returnParts);

        return sprintf("{%s}", $returnParts);
    }
}

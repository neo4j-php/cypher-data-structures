<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Helper;

use Stringable;
use Syndesi\CypherDataStructures\Contract\ConstraintInterface;
use Syndesi\CypherDataStructures\Contract\ConstraintNameInterface;
use Syndesi\CypherDataStructures\Contract\IndexInterface;
use Syndesi\CypherDataStructures\Contract\IndexNameInterface;
use Syndesi\CypherDataStructures\Contract\NodeInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelInterface;
use Syndesi\CypherDataStructures\Contract\NodeLabelStorageInterface;
use Syndesi\CypherDataStructures\Contract\OptionStorageInterface;
use Syndesi\CypherDataStructures\Contract\PropertyStorageInterface;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Contract\RelationTypeInterface;
use Syndesi\CypherDataStructures\Exception\InvalidArgumentException;
use Syndesi\CypherDataStructures\Type\ConstraintType;
use Syndesi\CypherDataStructures\Type\IndexType;
use Syndesi\CypherDataStructures\Type\PropertyName;

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

    /**
     * @throws InvalidArgumentException
     */
    public static function constraintToCypherString(ConstraintInterface $constraint): string
    {
        if (null === $constraint->getFor()) {
            throw InvalidArgumentException::createForTypeMismatch(NodeLabelInterface::class.'|'.RelationTypeInterface::class, 'null');
        }
        if (null === $constraint->getConstraintName()) {
            throw InvalidArgumentException::createForTypeMismatch(ConstraintNameInterface::class, 'null');
        }
        if (null === $constraint->getConstraintType()) {
            throw InvalidArgumentException::createForTypeMismatch(ConstraintType::class, 'null');
        }
        if (0 === $constraint->getProperties()->count()) {
            throw new InvalidArgumentException("At least one property is required");
        }

        $for = $constraint->getFor();
        if ($for instanceof NodeLabelInterface) {
            $forIdentifier = sprintf(
                "(element:%s)",
                $for->getNodeLabel()
            );
        } else {
            /**
             * @psalm-suppress PossiblyNullReference
             */
            $forIdentifier = sprintf(
                "()-[element:%s]-()",
                $for->getRelationType()
            );
        }

        $properties = [];
        /** @var PropertyName $propertyName */
        foreach ($constraint->getProperties() as $propertyName) {
            $properties[] = sprintf(
                "element.%s",
                $propertyName->getPropertyName()
            );
        }
        $properties = implode(', ', $properties);

        $optionsString = '';
        if ($constraint->getOptions()->count() > 0) {
            $optionsString = sprintf(
                " OPTIONS %s",
                self::optionStorageToCypherString($constraint->getOptions())
            );
        }

        /**
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyNullArgument
         * @psalm-suppress PossiblyNullPropertyFetch
         */
        return sprintf(
            "CONSTRAINT %s FOR %s REQUIRE (%s) IS %s%s",
            $constraint->getConstraintName()->getConstraintName(),
            $forIdentifier,
            $properties,
            $constraint->getConstraintType()->value,
            $optionsString
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function indexToCypherString(IndexInterface $index): string
    {
        if (null === $index->getFor()) {
            throw InvalidArgumentException::createForTypeMismatch(NodeLabelInterface::class.'|'.RelationTypeInterface::class, 'null');
        }
        if (null === $index->getIndexName()) {
            throw InvalidArgumentException::createForTypeMismatch(IndexNameInterface::class, 'null');
        }
        if (null === $index->getIndexType()) {
            throw InvalidArgumentException::createForTypeMismatch(IndexType::class, 'null');
        }
        if (0 === $index->getProperties()->count()) {
            throw new InvalidArgumentException("At least one property is required");
        }

        $for = $index->getFor();
        if ($for instanceof NodeLabelInterface) {
            $forIdentifier = sprintf(
                "(element:%s)",
                $for->getNodeLabel()
            );
        } else {
            /**
             * @psalm-suppress PossiblyNullReference
             */
            $forIdentifier = sprintf(
                "()-[element:%s]-()",
                $for->getRelationType()
            );
        }

        $properties = [];
        /** @var PropertyName $propertyName */
        foreach ($index->getProperties() as $propertyName) {
            $properties[] = sprintf(
                "element.%s",
                $propertyName->getPropertyName()
            );
        }
        $properties = implode(', ', $properties);

        $optionsString = '';
        if ($index->getOptions()->count() > 0) {
            $optionsString = sprintf(
                " OPTIONS %s",
                self::optionStorageToCypherString($index->getOptions())
            );
        }

        /**
         * @psalm-suppress PossiblyNullReference
         * @psalm-suppress PossiblyNullArgument
         * @psalm-suppress PossiblyNullPropertyFetch
         */
        return sprintf(
            "%s INDEX %s FOR %s ON (%s)%s",
            $index->getIndexType()->value,
            $index->getIndexName()->getIndexName(),
            $forIdentifier,
            $properties,
            $optionsString
        );
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
     * @throws InvalidArgumentException
     */
    public static function optionStorageToCypherString(OptionStorageInterface $optionStorage): string
    {
        $options = [];
        foreach ($optionStorage as $key) {
            $value = self::valueToString($optionStorage->offsetGet($key));
            $key = (string) $key;
            $options[$key] = $value;
        }
        ksort($options);
        $optionStringParts = [];
        foreach ($options as $key => $value) {
            if (str_contains($key, '.')) {
                $key = sprintf("`%s`", $key);
            }
            $optionStringParts[] = sprintf(
                "%s: %s",
                $key,
                $value
            );
        }

        $optionStringParts = implode(', ', $optionStringParts);

        return sprintf("{%s}", $optionStringParts);
    }
}

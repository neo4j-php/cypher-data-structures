<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use LogicException;
use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Contract\WeakRelationInterface;
use WeakReference;

class WeakRelation implements WeakRelationInterface
{
    private WeakReference $relation;

    public function __construct(RelationInterface $relation)
    {
        $this->relation = WeakReference::create($relation);
    }

    public function isEqualTo(mixed $element): null|bool
    {
        if (!($element instanceof WeakRelationInterface)) {
            return false;
        }

        return $this->get()?->isEqualTo($element->get());
    }

    public static function create(RelationInterface $relation): WeakRelationInterface
    {
        return new WeakRelation($relation);
    }

    public function get(): ?RelationInterface
    {
        $element = $this->relation->get();
        if (null === $element) {
            return null;
        }
        if (!($element instanceof RelationInterface)) {
            // @codeCoverageIgnoreStart
            throw new LogicException('Internal type missmatch');
            // @codeCoverageIgnoreEnd
        }

        return $element;
    }
}

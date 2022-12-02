<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

use Syndesi\CypherDataStructures\Contract\RelationInterface;
use Syndesi\CypherDataStructures\Contract\WeakRelationInterface;
use Syndesi\CypherDataStructures\Exception\LogicException;
use WeakReference;

class WeakRelation implements WeakRelationInterface
{
    private WeakReference $relation;

    public function __construct(RelationInterface $relation)
    {
        $this->relation = WeakReference::create($relation);
    }

    /**
     * @throws LogicException
     */
    public function isEqualTo(mixed $element): null|bool
    {
        if (!($element instanceof WeakRelationInterface)) {
            return false;
        }
        if (null === $this->get() || null === $element->get()) {
            return null;
        }

        /** @psalm-suppress PossiblyNullReference */
        return $this->get()->isEqualTo($element->get());
    }

    public static function create(RelationInterface $relation): WeakRelationInterface
    {
        return new WeakRelation($relation);
    }

    /**
     * @throws LogicException
     */
    public function get(): ?RelationInterface
    {
        $element = $this->relation->get();
        if (null === $element) {
            return null;
        }
        if (!($element instanceof RelationInterface)) {
            // @codeCoverageIgnoreStart
            throw LogicException::createForInternalTypeMismatch(RelationInterface::class, get_class($element));
            // @codeCoverageIgnoreEnd
        }

        return $element;
    }
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

/**
 * Classes implementing this interface should use PHP's WeakReference class internally.
 */
interface WeakRelationInterface extends NullableIsEqualToInterface
{
    public static function create(RelationInterface $relation): self;

    public function get(): ?RelationInterface;
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

use Stringable;

interface RelationInterface extends Stringable, IsEqualToInterface, HasIdentifiersInterface
{
    // start node

    public function setStartNode(?NodeInterface $node): self;

    public function getStartNode(): ?NodeInterface;

    // end node

    public function setEndNode(?NodeInterface $node): self;

    public function getEndNode(): ?NodeInterface;

    // relation type

    public function setType(?string $type): self;

    public function getType(): ?string;
}

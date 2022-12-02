<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface RelationInterface extends \Stringable, IsEqualToInterface, HasIdentifiersInterface
{
    public function setStartNode(?NodeInterface $node): self;

    public function getStartNode(): ?NodeInterface;

    public function setEndNode(?NodeInterface $node): self;

    public function getEndNode(): ?NodeInterface;

    public function setType(?string $type): self;

    public function getType(): ?string;
}

<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Contract;

interface IndexInterface extends \Stringable, IsEqualToInterface, HasPropertiesInterface, HasOptionsInterface
{
    public function getName(): ?string;

    public function setName(?string $name): self;

    public function getType(): ?string;

    public function setType(?string $type): self;

    public function getFor(): ?string;

    public function setFor(?string $for): self;
}

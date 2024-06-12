<?php

namespace Syndesi\CypherDataStructures\Contract;

use DateTimeInterface;

interface DateTimeConvertible
{
    public function toDateTime(): DateTimeInterface;

    public static function fromDateTime(DateTimeInterface $dateTime): self;
}
<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

enum ConstraintType: string
{
    case UNIQUE = 'UNIQUE';
    case NOT_NULL = 'NOT_NULL';
    case NODE_KEY = 'NODE_KEY';
}

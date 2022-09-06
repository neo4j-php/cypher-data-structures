<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Type;

enum IndexType: string
{
    case ALL = 'ALL';
    case BTREE = 'BTREE';
    case FULLTEXT = 'FULLTEXT';
    case LOOKUP = 'LOOKUP';
    case POINT = 'POINT';
    case RANGE = 'RANGE';
    case TEXT = 'TEXT';
}

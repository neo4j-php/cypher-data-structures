<?php

namespace Syndesi\CypherDataStructures\Contract;

/**
 * A class whose instance can be converted to a data type in packstream.
 *
 * @see https://neo4j.com/docs/bolt/current/packstream/
 */
interface PackstreamConvertible
{
    /**
     * Returns the marker that identifies the class to packstream.
     *
     * @return int
     */
    public function getPackstreamMarker(): int;
}
<?php

declare(strict_types=1);

/*
 * This file is part of the Neo4j PHP Client and Driver package.
 *
 * (c) Nagels <https://nagels.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Syndesi\CypherDataStructures\Type\OGM;

use Syndesi\CypherDataStructures\TypeCaster;

/**
 * A Relationship class representing a Relationship in cypher.
 *
 * @psalm-import-type OGMTypes from TypeCaster
 *
 * @psalm-immutable
 */
final class Relationship extends UnboundRelationship
{
    private int $startNodeId;

    private int $endNodeId;

    /**
     * @param Dictionary<OGMTypes> $properties
     */
    public function __construct(int $id, int $startNodeId, int $endNodeId, string $type, Dictionary $properties)
    {
        parent::__construct($id, $type, $properties);
        $this->startNodeId = $startNodeId;
        $this->endNodeId = $endNodeId;
    }

    /**
     * Returns the id of the start node.
     */
    public function getStartNodeId(): int
    {
        return $this->startNodeId;
    }

    /**
     * Returns the id of the end node.
     */
    public function getEndNodeId(): int
    {
        return $this->endNodeId;
    }

    /**
     * @psalm-suppress ImplementedReturnTypeMismatch False positive.
     *
     * @return array{
     *                id: int,
     *                type: string,
     *                startNodeId: int,
     *                endNodeId: int,
     *                properties: Dictionary<OGMTypes>
     *                }
     */
    public function toArray(): array
    {
        $tbr = parent::toArray();

        $tbr['startNodeId'] = $this->getStartNodeId();
        $tbr['endNodeId'] = $this->getEndNodeId();

        return $tbr;
    }

    public function getPackstreamMarker(): int
    {
        return 0x52;
    }
}

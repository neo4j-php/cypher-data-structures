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
use Syndesi\CypherDataStructures\Exception\PropertyDoesNotExistException;

use function sprintf;

/**
 * A Node class representing a Node in cypher.
 *
 * @psalm-import-type OGMTypes from TypeCaster
 *
 * @psalm-immutable
 *
 * @extends AbstractPropertyObject<OGMTypes, int|string|Dictionary<OGMTypes>>
 * @extends AbstractPropertyObject<OGMTypes, int|CypherList<string>|Dictionary<OGMTypes>>
 */
final class Node extends AbstractPropertyObject
{
    /**
     * @param CypherList<string>  $labels
     * @param Dictionary<OGMTypes> $properties
     */
    public function __construct(private int $id, private CypherList $labels, private Dictionary $properties)
    {
    }

    /**
     * The labels on the node.
     *
     * @return CypherList<string>
     */
    public function getLabels(): CypherList
    {
        return $this->labels;
    }

    /**
     * The id of the node.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the property of the node by key.
     *
     * @return OGMTypes
     */
    public function getProperty(string $key)
    {
        /** @psalm-suppress ImpureMethodCall */
        if (!$this->properties->hasKey($key)) {
            throw new PropertyDoesNotExistException(sprintf('Property "%s" does not exist on node', $key));
        }

        /** @psalm-suppress ImpureMethodCall */
        return $this->properties->get($key);
    }

    /**
     * @psalm-suppress ImplementedReturnTypeMismatch False positive.
     *
     * @return array{id: int, labels: CypherList<string>, properties: Dictionary<OGMTypes>}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'labels' => $this->labels,
            'properties' => $this->properties,
        ];
    }

    public function getProperties(): Dictionary
    {
        /** @psalm-suppress InvalidReturnStatement false positive with type alias. */
        return $this->properties;
    }

    public function getPackstreamMarker(): int
    {
        return 0x4E;
    }
}

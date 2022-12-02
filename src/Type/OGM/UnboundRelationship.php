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

use Syndesi\CypherDataStructures\Exception\PropertyDoesNotExistException;
use Syndesi\CypherDataStructures\TypeCaster;

use function sprintf;

/**
 * A relationship without any nodes attached to it.
 *
 * @psalm-import-type OGMTypes from TypeCaster
 *
 * @psalm-immutable
 *
 * @extends AbstractPropertyObject<OGMTypes, int|string|Dictionary<OGMTypes>>
 */
class UnboundRelationship extends AbstractPropertyObject
{
    private int $id;
    private string $type;
    /** @var Dictionary<OGMTypes> */
    private Dictionary $properties;

    /**
     * @param Dictionary<OGMTypes> $properties
     */
    public function __construct(int $id, string $type, Dictionary $properties)
    {
        $this->id = $id;
        $this->type = $type;
        $this->properties = $properties;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getProperties(): Dictionary
    {
        /** @psalm-suppress InvalidReturnStatement false positive with type alias. */
        return $this->properties;
    }

    /**
     * @psalm-suppress ImplementedReturnTypeMismatch False positive.
     *
     * @return array{id: int, type: string, properties: Dictionary<OGMTypes>}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'properties' => $this->getProperties(),
        ];
    }

    /**
     * Gets the property of the relationship by key.
     *
     * @return OGMTypes
     */
    public function getProperty(string $key)
    {
        /** @psalm-suppress ImpureMethodCall */
        if (!$this->properties->hasKey($key)) {
            throw new PropertyDoesNotExistException(sprintf('Property "%s" does not exist on relationship', $key));
        }

        /** @psalm-suppress ImpureMethodCall */
        return $this->properties->get($key);
    }

    public function getPackstreamMarker(): int
    {
        return 0x72;
    }
}

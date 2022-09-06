<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Benchmark;

use PhpBench\Attributes as Bench;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Type\Relation;
use Syndesi\CypherDataStructures\Type\RelationType;

class RelationBench
{
    public function provideBenchRelationsCount(): array
    {
        return [
            '0' => [0],
            '10' => [10],
            '100' => [100],
            '1000' => [1000],
            '2000' => [2000],
            '4000' => [4000],
            '6000' => [6000],
            '8000' => [8000],
            '10000' => [10000],
        ];
    }

    #[Bench\Revs(10)]
    #[Bench\ParamProviders('provideBenchRelationsCount')]
    public function benchRelations(array $params): void
    {
        $relations = [];
        for ($i = 0; $i < $params[0]; ++$i) {
            $startNode = new Node();
            $startNode
                ->addNodeLabel(new NodeLabel('StartNode'))
                ->addProperty(new PropertyName('id'), rand())
                ->addIdentifier(new PropertyName('id'));

            $endNode = new Node();
            $endNode
                ->addNodeLabel(new NodeLabel('EndNode'))
                ->addProperty(new PropertyName('id'), rand())
                ->addIdentifier(new PropertyName('id'));

            $relation = new Relation();
            $relation
                ->setStartNode($startNode)
                ->setEndNode($endNode)
                ->setRelationType(new RelationType('TYPE'))
                ->addProperty(new PropertyName('id'), rand())
                ->addProperty(new PropertyName('a'), 'some value for a')
                ->addProperty(new PropertyName('b'), 'some value for b')
                ->addProperty(new PropertyName('c'), 'some value for c')
                ->addProperty(new PropertyName('d'), 'some value for d')
                ->addIdentifier(new PropertyName('id'));
            $relations[] = $relation;
        }
    }
}

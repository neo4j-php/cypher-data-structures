<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Benchmark;

use PhpBench\Attributes as Bench;
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\Relation;

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
            $startNode = (new Node())
                ->addLabel('StartNode')
                ->addProperty('id', rand())
                ->addIdentifier('id');

            $endNode = (new Node())
                ->addLabel('StartNode')
                ->addProperty('id', rand())
                ->addIdentifier('id');

            $relation = (new Relation())
                ->setStartNode($startNode)
                ->setEndNode($endNode)
                ->setType('TYPE')
                ->addProperties([
                    'a' => 'some value for a',
                    'b' => 'some value for b',
                    'c' => 'some value for c',
                    'd' => 'some value for d',
                    'id' => rand()
                ])
                ->addIdentifier('id');
            $relations[] = $relation;
        }
    }
}

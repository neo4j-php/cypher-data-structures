<?php

declare(strict_types=1);

namespace Syndesi\CypherDataStructures\Benchmark;

use PhpBench\Attributes as Bench;
use Syndesi\CypherDataStructures\Type\OGM\Node;
use Syndesi\CypherDataStructures\Type\OGM\NodeLabel;
use Syndesi\CypherDataStructures\Type\PropertyName;

class NodeBench
{
    public function provideBenchNodesCount(): array
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
    #[Bench\ParamProviders('provideBenchNodesCount')]
    public function benchNodes(array $params): void
    {
        $nodes = [];
        for ($i = 0; $i < $params[0]; ++$i) {
            $node = new Node();
            $node
                ->addNodeLabel(new NodeLabel('LabelA'))
                ->addNodeLabel(new NodeLabel('LabelB'))
                ->addProperty(new PropertyName('a'), 'some value for a')
                ->addProperty(new PropertyName('b'), 'some value for b')
                ->addProperty(new PropertyName('c'), 'some value for c')
                ->addProperty(new PropertyName('d'), 'some value for d')
                ->addProperty(new PropertyName('id'), rand())
                ->addIdentifier(new PropertyName('id'));
            $nodes[] = $node;
        }
    }
}

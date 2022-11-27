# Benchmark

Benchmarks are created using the [PHPBench framework](https://github.com/phpbench/phpbench).  
Currently the tests are run inside Docker with [Xdebug](https://xdebug.org/) enabled. On bare metal, without Xdebug,
optimized setup etc. the performance should increase quite a bit.

## Node

Time and memory increase linearly with the amount of instances created.  
An average node with two labels, four string properties and an integer id requires ~1.2kb of memory and is created
within ~0.002 ms

![Plot of number of nodes vs time](./assets/nodes_time.png)
![Plot of number of nodes vs memory](./assets/nodes_memory.png)

## Relation

Time and memory increase linearly with the amount of instances created.  
An average relation with two minimal nodes (one label, one integer id), one type, four string properties and one integer
id requires ~3.3kb of memory and is created within ~0.006ms.

![Plot of number of relations vs time](./assets/relations_time.png)
![Plot of number of relations vs memory](./assets/relations_memory.png)

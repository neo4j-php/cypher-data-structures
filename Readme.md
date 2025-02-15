[![GitHub](https://img.shields.io/github/license/neo4j-php/cypher-data-structures)](https://github.com/neo4j-php/cypher-data-structures/blob/main/LICENSE)
![Packagist PHP Version Support (specify version)](https://img.shields.io/packagist/php-v/syndesi/cypher-data-structures/dev-main)
![Packagist Version](https://img.shields.io/packagist/v/syndesi/cypher-data-structures)
![Packagist Downloads](https://img.shields.io/packagist/dm/syndesi/cypher-data-structures)

[![Test Coverage](https://api.codeclimate.com/v1/badges/3a6aef038839e5bb5b59/test_coverage)](https://codeclimate.com/github/Syndesi/cypher-data-structures/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/3a6aef038839e5bb5b59/maintainability)](https://codeclimate.com/github/Syndesi/cypher-data-structures/maintainability)

# Syndesi's Cypher Data Structures

This library provides basic data classes, so that working with Cypher based graph databases becomes easy.

- [Documentation](https://neo4j-php.github.io/cypher-data-structures)
- [Packagist](https://packagist.org/packages/syndesi/cypher-data-structures)

## Installation

To install this library, run the following code:

```bash
composer require syndesi/cypher-data-structures
```

This is all, now you can use the library :D

## Using the library

```php
use Syndesi\CypherDataStructures\Type\Node;
use Syndesi\CypherDataStructures\Type\Relation;

$node = new Node();
$node
    ->addLabel('NodeLabel')
    ->addIdentifier('id', 123)
    ->addProperty('someProperty', 'someValue')
    ->addIdentifier('id');

$otherNode = new Node();
$otherNode
    ->addLabel('OtherNodeLabel')
    ->addIdentifier('id', 234)
    ->addProperty('hello', 'world :D')
    ->addIdentifier('id');

$relation = new Relation();
$relation
    ->setStartNode($node)
    ->setEndNode($node)
    ->setType('SOME_RELATION');
```

## Advanced integration

This library itself does not provide advanced features like validation. Those are separated into their own projects:

- Validation: Work in progress, not yet released.
- [Entity Manager](https://github.com/neo4j-php/cypher-entity-manager): Automatically creates and runs Cypher statements
  from data objects of this library for you.

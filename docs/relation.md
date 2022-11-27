# Relation

Relations are a fundamental element of graph databases as they connect two nodes.  
They can be created as following:

```php
use Syndesi\CypherDataStructures\Type\Relation;

$relation = new Relation();
```

## Type

Types identify the type of relationship, e.g. a relation with type `CREATED_BY` would mean that the start node was
created by the end node of the relationship.  
A relation must have exactly one relationship type.  
Types [should be written in UPPERCASE_SNAKE_CASE](https://neo4j.com/docs/cypher-manual/current/syntax/naming/#_recommendations).

```php
$relation = new \Syndesi\CypherDataStructures\Type\Relation();

// set the type of relationship:
$relation->setType('RELATION');

// get the type of relationship
$relation->getType();
```

## Properties

Properties are key-value-pairs which can store data within a relationship.  
The keys must be unique and [should be written in camelCase](https://neo4j.com/docs/cypher-manual/current/styleguide/#cypher-styleguide-casing).

!> **Note**: While this library supports arrays and objects as property values, Neo4j has limited support for those types.  
Be sure that those types are correctly handled.

```php
// add property to a relationship:
$relationship->addProperty('propertyName', 'property value');

// add multiple properties to a relationship:
$relationship->addProperties([
    'id' => 123,
    'hello' => 'world :D'
]);

// check if a relationship has a specific property:
$relationship->hasProperty('id');

// get the value of a specific property:
$relationship->getProperty('id');

// get all properties from a relationship:
$relationship->getProperties();

// remove a specific property from a relationship:
$relationship->removeProperty('hello');

// remove all properties from a relationship:
$relationship->removeProperties();
```

### Identifying Properties

Identifying properties are just normal properties which are marked to identify the relationship uniquely within the
database.

!> **Note**: Identifying properties are not part of the OpenCypher specification.

!> **Important**: Only existing properties can be marked as "identifying". Also, identifying properties can not be
removed from the relationship as long as the identifying-mark is not removed.

```php
// add identifying property to a relationship:
$relationship->addProperty('id', 123);
$relationship->addIdentifier('id');

// add multiple identifying properties to a relationship:
$relationship->addProperties([
    'id2' => '234',
    'id3' => '345'
]);
$relationship->addIdentifiers(['id2', 'id3']);

// check if relationship has a specific identifying property:
$relationship->hasIdentifier('id');

// get the value of a specific identifying property:
$relationship->getIdentifier('id');

// get all identifying properties:
$relationship->getIdentifiers();

// remove a specific identifying property mark without removing the property itself:
$relationship->removeIdentifier('id');

// remove all identifying property marks without removing the properties themselves:
$relationship->removeProperties();
```

## Start and End Nodes

Every relation must start at a [node](node.md) (start node) and end at a node (end node).  
The start and end nodes can be the same node.  
The relationship points to the end node:

```cypher
(startNode)-[relation]->(endNode)
```

!> **Important**: Setting the start or end node of a relationship does not automatically add the relation to the start
   or end node.

```php
use Syndesi\CypherDataStructures\Type\Node;

$startNode = new Node();

// set the start node of a relationship:
$relation->setStartNode($startNode);

// get the start node of a relationship:
$relation->getStartNode();

$endNode = new Node();

// set the end node of a relationship:
$relation->setEndNode($endNode);

// get the end node of a relationship:
$relation->getEndNode();
```

# Node

Nodes are the most basic data elements within a graph database.  
They can be created as following:

```php
use Syndesi\CypherDataStructures\Type\Node;

$node = new Node();
```

## Labels

Labels identify the node's type, e.g. a label called `User` would identify a node as a user and `Document` would
identify it as a document.  
Nodes can have zero, one or more labels, although one label is the default.  
Labels [should be written in CamelCase](https://neo4j.com/docs/cypher-manual/current/syntax/naming/#_recommendations).

```php
// add a single label to a node:
$node->addLabel('FirstLabel');

// add multiple labels to a node:
$node->addLabels(['SecondLabel', 'ThirdLabel']);

// check if a node has a label:
$node->hasLabel('FirstLabel');

// get all labels from a node:
$node->getLabels();

// remove a label from a node:
$node->removeLabel('ThirdLabel');

// remove all labels from a node:
$node->removeLabels();
```

## Properties

Properties are key-value-pairs which can store data within a node.  
The keys must be unique and [should be written in camelCase](https://neo4j.com/docs/cypher-manual/current/styleguide/#cypher-styleguide-casing).

!> **Note**: While this library supports arrays and objects as property values, Neo4j has limited support for those
   types.  
   Be sure that those types are correctly handled.

```php
// add property to a node:
$node->addProperty('propertyName', 'property value');

// add multiple properties to a node:
$node->addProperties([
    'id' => 123,
    'hello' => 'world :D'
]);

// check if a node has a specific property:
$node->hasProperty('id');

// get the value of a specific property:
$node->getProperty('id');

// get all properties from a node:
$node->getProperties();

// remove a specific property from a node:
$node->removeProperty('hello');

// remove all properties from a node:
$node->removeProperties();
```

### Identifying Properties

Identifying properties are just normal properties which are marked to identify the node uniquely within the database.

!> **Note**: Identifying properties are not part of the OpenCypher specification.

!> **Important**: Only existing properties can be marked as "identifying". Also, identifying properties can not be removed
   from the node as long as the identifying-mark is not removed.

```php
// add identifying property to a node:
$node->addProperty('id', 123);
$node->addIdentifier('id');

// add multiple identifying properties to a node:
$node->addProperties([
    'id2' => '234',
    'id3' => '345'
]);
$node->addIdentifiers(['id2', 'id3']);

// check if node has a specific identifying property:
$node->hasIdentifier('id');

// get the value of a specific identifying property:
$node->getIdentifier('id');

// get all identifying properties:
$node->getIdentifiers();

// remove a specific identifying property mark without removing the property itself:
$node->removeIdentifier('id');

// remove all identifying property marks without removing the properties themselves:
$node->removeProperties();
```

## Relations

Nodes can relate to other nodes and are connected to them via [relations](relation.md). Those must either start or end
at the current node.

```php
$node = new \Syndesi\CypherDataStructures\Type\Node();
use Syndesi\CypherDataStructures\Type\Relation;

$relation = new Relation();
$relation->setStartNode($node); // important: Relations must either start or end at the node itself
$relation->setType('RELATION');

// add relation to node:
$node->addRelation($relation);

// add relations to node:
$node->addRelations([
    $relation,
    clone $relation
]);

// check if a node has a specific relation
$node->hasRelation($relation);

// get all relations from a node
$node->getRelations();

// remove a specific relation from a node:
$node->removeRelation($relation);

// remove all relations from a node:
$node->removeRelations();
```

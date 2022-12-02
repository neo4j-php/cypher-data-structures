# Index

Indexes are hints for the database to optimize the way data is stored so that specific operations are faster.  
There are two different types of indexes, one for nodes and one for relationships.  
They can be created as following:

```php
use Syndesi\CypherDataStructures\Type\NodeIndex;
use Syndesi\CypherDataStructures\Type\RelationIndex;

$nodeIndex = new NodeIndex();
$relationIndex = new RelationIndex();

// note: the later examples use $someIndex when the specific type does not matter
```

!> **Important**: Indexes are not part of the OpenCypher specification and are different for each database type.

!> **Note**: The creation of constraints might create internal indexes as well.

## Name

The name of indexes must be unique across the whole database.  
The name is usually written in [lowercase snake case](https://neo4j.com/docs/cypher-manual/current/indexes-for-search-performance/#administration-indexes-examples).

```php
// set the name of an index:
$someIndex->setName('some_name');

// get the name of an index:
$someIndex->getName();
```

## For

Indexes are always created for a specific node or relationship label/type.

```php
// set the node label for a node label index:
$nodeIndex->setFor('NodeLabel');

// set the relationship type for a relationship index:
$relationIndex->setFor('RELATIONSHIP_TYPE');

// get the node label or relationship type from an index, depending on the index type:
$someIndex->getFor();
```

## Type

Indexes have a specific type, e.g. `BTREE` or `RANGE`. These types depend on the database as well as the database
version.

!> **Important**: Depending on the database, not all index types are available for nodes as well as relationships.

```php
// set the type of index:
$someIndex->setType('BTREE');

// get the type of index:
$someIndex->getType();
```

## Properties

Indexes can specify the properties of a node/relationship on which they should act.

!> **Note**: Most of the time only the property names are important. Setting the property values to null is therefore
   ok.

```php
// add property to an index with default value null:
$someIndex->addProperty('propertyName');

// add multiple properties to an index:
$someIndex->addProperties([
    'id' => null,
    'hello' => 'world :D'
]);

// check if an index has a specific property:
$someIndex->hasProperty('id');

// get the value of a specific property:
$someIndex->getProperty('id');

// get all properties from an index:
$someIndex->getProperties();

// remove a specific property from an index:
$someIndex->removeProperty('hello');

// remove all properties from an index:
$someIndex->removeProperties();
```

## Options

Some indexes can be configured via options.  
The example uses strings, but all types (arrays, integers etc.) are supported.

```php
// add a single option
$someIndex->addOption('name', 'value');

// add multiple options
$someIndex->addOptions([
    'other.name' => 'other value',
    'some.name' => 'some value'
]);

// check if index has option
$someIndex->hasOption('name');

// get specific option from an index
$someIndex->getOption('name');

// get all options from an index
$someIndex->getOptions();

// remove a specific option from an index:
$someIndex->removeOption('name');

// remove all options from an index:
$someIndex->removeOptions();
```

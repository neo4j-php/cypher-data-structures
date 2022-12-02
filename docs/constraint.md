# Constraint

Constraints are used to enforce specific data schemas within a database.  
There are two different types of constraints, one for nodes and one for relationships.  
They can be created as following:

```php
use Syndesi\CypherDataStructures\Type\NodeConstraint;
use Syndesi\CypherDataStructures\Type\RelationConstraint;

$nodeConstraint = new NodeConstraint();
$relationConstraint = new RelationConstraint();

// note: the later examples use $someConstraint when the specific type does not matter
```

!> **Important**: Constraints are not part of the OpenCypher specification and are different for each database type.

!> **Note**: The creation of constraints might create internal indexes as well.

## Name

The name of constraints must be unique across the whole database.  
The name is usually written in [lowercase snake case](https://neo4j.com/docs/cypher-manual/current/constraints/examples/).

```php
// set the name of a constraint:
$someConstraint->setName('some_name');

// get the name of a constraint:
$someConstraint->getName();
```

## For

Constraints are always created for a specific node or relationship label/type.

```php
// set the node label for a node label constraint:
$nodeConstraint->setFor('NodeLabel');

// set the relationship type for a relationship constraint:
$relationConstraint->setFor('RELATIONSHIP_TYPE');

// get the node label or relationship type from a constraint, depending on the constraint type:
$someConstraint->getFor();
```

## Type

Constraints have a specific type, e.g. `UNIQUE`. These types depend on the database as well as the database  version.

!> **Important**: Depending on the database, not all constraint types are available for nodes as well as relationships.

```php
// set the type of constraint:
$someConstraint->setType('UNIQUE');

// get the type of constraint:
$someConstraint->getType();
```

## Properties

Constraints can specify the properties of a node/relationship on which they should act.

!> **Note**: Most of the time only the property names are important. Setting the property values to null is therefore
ok.

```php
// add property to a constraint with default value null:
$someConstraint->addProperty('propertyName');

// add multiple properties to a constraint:
$someConstraint->addProperties([
    'id' => null,
    'hello' => 'world :D'
]);

// check if a constraint has a specific property:
$someConstraint->hasProperty('id');

// get the value of a specific property:
$someConstraint->getProperty('id');

// get all properties from a constraint:
$someConstraint->getProperties();

// remove a specific property from a constraint:
$someConstraint->removeProperty('hello');

// remove all properties from a constraint:
$someConstraint->removeProperties();
```

## Options

Some constraints can be configured via options.  
The example uses strings, but all types (arrays, integers etc.) are supported.

```php
// add a single option
$someConstraint->addOption('name', 'value');

// add multiple options
$someConstraint->addOptions([
    'other.name' => 'other value',
    'some.name' => 'some value'
]);

// check if constraint has option
$someConstraint->hasOption('name');

// get specific option from a constraint
$someConstraint->getOption('name');

// get all options from a constraint
$someConstraint->getOptions();

// remove a specific option from a constraint:
$someConstraint->removeOption('name');

// remove all options from a constraint:
$someConstraint->removeOptions();
```

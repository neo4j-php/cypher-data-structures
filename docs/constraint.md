# Constraint

Constraints are entities which contain the following attributes:

- **ConstraintName**: Zero or one constraint name, usually one. They are basically strings with validation. They must be
  snake_case as per [Neo4j's examples](https://neo4j.com/docs/cypher-manual/current/constraints/examples/).  
  Constraint names can start with a single underscore, although this is reserved for internal logic.  
  You can overwrite the validation part by creating your own implementation of
  `Syndesi\CypherDataStructures\Contract\ConstraintNameInterface`.
- **constraintType**: Defines how the constraint works, must be set manually.
- **For**: Can be either a `NodeLabel` or `RelationType`.
- **Properties**: Properties on which the constraint applies to. At least one is required.
- **Options**: Options which configure constraint dependant settings, usually empty.

## Examples

```php
use Syndesi\CypherDataStructures\Type\ConstraintName;
use Syndesi\CypherDataStructures\Type\Constraint;
use Syndesi\CypherDataStructures\Type\ConstraintType;
use Syndesi\CypherDataStructures\Type\NodeLabel;
use Syndesi\CypherDataStructures\Type\PropertyName;

$constraint = new Constraint();
$constraint
    ->setConstraintName(new ConstraintName('some_name'))
    ->setConstraintType(ConstraintType::UNIQUE)
    ->setFor(new NodeLabel('SomeNode'))
    ->addProperty(new PropertyName('id'));
```

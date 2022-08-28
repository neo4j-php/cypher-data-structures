# Constraint

Constraints are entities which contain the following attributes:

- **ConstraintName**: Zero or one constraint name, usually one. They are basically strings with validation. They must be
  snake_case as per [Neo4j's examples](https://neo4j.com/docs/cypher-manual/current/constraints/examples/).  
  Constraint names can start with a single underscore, although this is reserved for internal logic.  
  You can overwrite the validation part by creating your own implementation of
  `Syndesi\CypherDataStructures\Contract\ConstraintNameInterface`.

## Examples

```php
use \Syndesi\CypherDataStructures\Type\ConstraintName;

$constraintName = new ConstraintName('some_name');
```

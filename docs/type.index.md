# Index

Indexes are entities which contain the following attributes:

- **IndexName**: Zero or one index name, usually one. They are basically strings with validation. They must be
  snake_case as per [Neo4j's examples
  ](https://neo4j.com/docs/cypher-manual/current/indexes-for-search-performance/#administration-indexes-examples).  
  Index names can start with a single underscore, although this is reserved for internal logic.  
  You can overwrite the validation part by creating your own implementation of
  `Syndesi\CypherDataStructures\Contract\IndexNameInterface`.
- **IndexType**: Defines how the index works, should usually be set to `BTREE`.
- **For**: Can be either a `NodeLabel` or `RelationType`.
- **Properties**: Properties on which the index applies to. At least one is required.
- **Options**: Options which configure index dependant settings, usually empty.

## Examples

```php
use Syndesi\CypherDataStructures\Type\IndexName;
use Syndesi\CypherDataStructures\Type\Index;
use Syndesi\CypherDataStructures\Type\IndexType;
use Syndesi\CypherDataStructures\Type\OGM\NodeLabel;
use Syndesi\CypherDataStructures\Type\PropertyName;

$index = new Index();
$index
    ->setIndexName(new IndexName('some_name'))
    ->setIndexType(IndexType::BTREE)
    ->setFor(new NodeLabel('SomeNode'))
    ->addProperty(new PropertyName('id'));
```

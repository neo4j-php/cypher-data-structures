# Index

Indexes are entities which contain the following attributes:

- **IndexName**: Zero or one index name, usually one. They are basically strings with validation. They must be
  snake_case as per [Neo4j's examples
  ](https://neo4j.com/docs/cypher-manual/current/indexes-for-search-performance/#administration-indexes-examples).  
  Index names can start with a single underscore, although this is reserved for internal logic.  
  You can overwrite the validation part by creating your own implementation of
  `Syndesi\CypherDataStructures\Contract\IndexNameInterface`.

## Examples

```php
use \Syndesi\CypherDataStructures\Type\IndexName;

$indexName = new IndexName('some_name');
```

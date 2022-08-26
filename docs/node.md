# Node

Nodes are entities which contain the following attributes:

- **NodeLabels**: Zero or more node labels, usually one. They are basically strings with validation. They must be
  CamelCase as per [Neo4j's documentation](https://neo4j.com/docs/cypher-manual/current/syntax/naming/#_recommendations)
  .  
  Node labels can start with a single underscore, although this is reserved for internal logic.  
  You can overwrite the validation part by creating your own implementation of
  `Syndesi\CypherDataStructures\Contract\NodeLabelInterface`.

- **Properties**: Zero or more properties, which are composed of one `PropertyName` for the key and a mixed property value.
  Within the node they are stored as an array-like storage. for more information see [properties](properties.md).

- **Identifiers**: The names of zero or more properties which uniquely identify the node. The referenced properties must
  contain a value.  
  When removing identifying properties without removing the identifier first, an exception is triggered.

- **Relations**: A list of relations which either start or end at the node.

## Examples

```php
use \Syndesi\CypherDataStructures\Type\NodeLabel;

$nodeLabel = new NodeLabel("SomeNode");
```
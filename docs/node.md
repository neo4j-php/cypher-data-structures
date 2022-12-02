# Node

Nodes are entities which contain the following attributes:

- **NodeLabels**: Zero or more node labels, usually one. They are basically strings with validation. They must be
  CamelCase as per [Neo4j's documentation](https://neo4j.com/docs/cypher-manual/current/syntax/naming/#_recommendations)
  .  
  Node labels can start with a single underscore, although this is reserved for internal logic.  
  You can overwrite the validation part by creating your own implementation of
  `Syndesi\CypherDataStructures\Contract\NodeLabelInterface`.

- **Properties**: Zero or more properties, which are composed of one `PropertyName` for the key and a mixed property value.
  Within the node they are stored as an array-like storage. For more information see [property](property.md).

- **Identifiers**: The names of zero or more properties which uniquely identify the node. The referenced properties must
  contain a value.  
  When removing identifying properties without removing the identifier first, an exception is triggered.

- **Relations**: A list of relations which either start or end at the node.  
  The default implementation stores relations in [weak references](https://www.php.net/manual/en/class.weakreference.php)
  so when a relation is unset, the node's relation will become `null`. This is done to remove the possibility of cyclic
  references.  
  Relations must contain the node itself as either the start or end node.  
  
!> **Note**: Make sure that identifying elements (node labels, relation type, identifying properties) are set before
   referencing nodes to relations and vice versa.  
   Changing those elements later does not update the existing references.

## Examples

```php
use Syndesi\CypherDataStructures\Type\OGM\NodeLabel;
use Syndesi\CypherDataStructures\Type\OGM\Node;
use Syndesi\CypherDataStructures\Type\PropertyName;

$node = new Node();
$node
    ->addNodeLabel(new NodeLabel("SomeNode"))
    ->addProperty(new PropertyName("id"), 1234)
    ->addIdentifier(new PropertyName("id"));
```

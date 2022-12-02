# Relation

Relations are entities which contain the following attributes:

- **StartNode**: The node where the relation starts. Usually only the identifying attributes need to be present, e.g.
  node labels and identifying properties.

- **EndNode**: The node where the relation ends. Same requirements as for the StartNode.

- **RelationType**: The type of the relation, optional.

- **Properties**: Zero or more properties, which are composed of one `PropertyName` for the key and a mixed property
  value. Within the relation they are stored as an array-like storage. for more information see
  [properties](properties.md).

- **Identifiers**: The names of zero or more properties which uniquely identify the relation. The referenced properties
  must contain a value.  
  When removing identifying properties without removing the identifier first, an exception is triggered.

!> **Note**: Make sure that identifying elements (node labels, relation type, identifying properties) are set before
   referencing nodes to relations and vice versa.  
   Changing those elements later does not update the existing references.

## Examples

```php
use Syndesi\CypherDataStructures\Type\OGM\Node;use Syndesi\CypherDataStructures\Type\OGM\NodeLabel;use Syndesi\CypherDataStructures\Type\OGM\Relation;use Syndesi\CypherDataStructures\Type\OGM\RelationType;use Syndesi\CypherDataStructures\Type\PropertyName;

$nodeA = new Node();
$nodeA
    ->addNodeLabel(new NodeLabel("NodeA"));

$nodeB = new Node();
$nodeB
    ->addNodeLabel(new NodeLabel("NodeB"));

$relation = new Relation();
$relation
    ->setRelationType(new RelationType("SOME_TYPE"))
    ->setStartNode($nodeA)
    ->setEndNode($nodeB)
    ->addProperty(new PropertyName("id"), 1234)
    ->addIdentifier(new PropertyName("id"));
```

# Helper

## To Cypher helper

A group of functions which are designed to take types from this library and convert them to Cypher-strings.

### Property storage to Cypher property string

Transforms objects of type `PropertyStorageInterface` to a Cypher property string with sorted names.  
Internal properties will be sorted first.

```php
use \Syndesi\CypherDataStructures\Type\PropertyStorage;
use \Syndesi\CypherDataStructures\Type\PropertyName;
use \Syndesi\CypherDataStructures\Helper\ToCypherHelper;

$propertyStorage = new PropertyStorage();
$propertyStorage->attach(new PropertyName('propertyA'), 'value A');
$propertyStorage->attach(new PropertyName('propertyZ'), 'value Z');
$propertyStorage->attach(new PropertyName('propertyB'), 'value B');
$propertyStorage->attach(new PropertyName('_internal'), 'value _');

echo(ToCypherHelper::propertyStorageToCypherPropertyString($propertyStorage));
// > _internal: 'value _', propertyA: 'value A', propertyB: 'value B', propertyZ: 'value Z'
```

### Node label storage to Cypher label string

Transforms objects of type `NodeLabelInterface` to a Cypher label string with sorted labels.  
Internal labels will be sorted first.

```php
use \Syndesi\CypherDataStructures\Helper\ToCypherHelper;
use \Syndesi\CypherDataStructures\Type\NodeLabelStorage;
use \Syndesi\CypherDataStructures\Type\NodeLabel;

$nodeLabelStorage = new NodeLabelStorage();
$nodeLabelStorage->attach(new NodeLabel('LabelA'));
$nodeLabelStorage->attach(new NodeLabel('LabelZ'));
$nodeLabelStorage->attach(new NodeLabel('LabelB'));
$nodeLabelStorage->attach(new NodeLabel('_Internal'));

echo(ToCypherHelper::nodeLabelStorageToCypherLabelString($nodeLabelStorage));
// > :_Internal:LabelA:LabelB:LabelC
```

### Node to Cypher string

Transforms objects of type `NodeInterface` to a Cypher node string, optionally limiting properties to identifying
ones.

```php
use \Syndesi\CypherDataStructures\Type\Node;
use \Syndesi\CypherDataStructures\Type\PropertyName;
use \Syndesi\CypherDataStructures\Type\NodeLabel;
use \Syndesi\CypherDataStructures\Helper\ToCypherHelper;

$node = new Node();
$node->addNodeLabel(new NodeLabel('Label'));
$node->addProperty(new PropertyName('id'), 1234);
$node->addProperty(new PropertyName('someKey'), 'some value');
$node->addIdentifier(new PropertyName('id'));

// print whole node
echo(ToCypherHelper::nodeToCypherString($node));
// > (:Label {id: '1234', someKey: 'some value'})

// print node with only identifying properties
echo(ToCypherHelper::nodeToCypherString($node, true));
// > (:Label {id: '1234'})

// alias to previous method
echo(ToCypherHelper::nodeToIdentifyingCypherString($node));
// > (:Label {id: '1234'})
```

### Relation to Cypher string

Transforms objects of type `RelationInterface` to a Cypher relation string, optionally limiting properties to
identifying ones.  
Referenced nodes are limited to identifying properties. Both nodes can be omitted.

```php
use \Syndesi\CypherDataStructures\Type\Node;
use \Syndesi\CypherDataStructures\Type\NodeLabel;
use \Syndesi\CypherDataStructures\Type\PropertyName;
use \Syndesi\CypherDataStructures\Type\Relation;
use \Syndesi\CypherDataStructures\Type\RelationType;
use \Syndesi\CypherDataStructures\Helper\ToCypherHelper;

$startNode = new Node();
$startNode->addNodeLabel(new NodeLabel('StartNode'));
$startNode->addProperty(new PropertyName('id'), 1234);
$startNode->addProperty(new PropertyName('someKey'), 'some value');
$startNode->addIdentifier(new PropertyName('id'));

$endNode = new Node();
$endNode->addNodeLabel(new NodeLabel('EndNode'));
$endNode->addProperty(new PropertyName('id'), 4321);
$endNode->addProperty(new PropertyName('otherKey'), 'other value');
$endNode->addIdentifier(new PropertyName('id'));

$relation = new Relation();
$relation->setRelationType(new RelationType('TYPE'));
$relation->setStartNode($startNode);
$relation->setEndNode($endNode);
$relation->addProperty(new PropertyName('id'), 123);
$relation->addProperty(new PropertyName('key'), 'value');
$relation->addIdentifier(new PropertyName('id'));

// print relation with all properties & nodes
echo(ToCypherHelper::relationToCypherString($relation));
// > (:StartNode {id: '1234'})-[:TYPE {id: '123', key: 'value'}]->(:EndNode {id: 4321})

// print relation with identifying properties & nodes
echo(ToCypherHelper::relationToCypherString($relation, true));
// > (:StartNode {id: '1234'})-[:TYPE {id: '123'}]->(:EndNode {id: 4321})

// alias to previous method
echo(ToCypherHelper::relationToIdentifyingCypherString($relation));
// > (:StartNode {id: '1234'})-[:TYPE {id: '123'}]->(:EndNode {id: 4321})

// print relation with properties, no nodes
echo(ToCypherHelper::relationToCypherString($relation, withNodes: false));
// > [:TYPE {id: '123', key: 'value'}]

// print relation with identifying properties, no nodes
echo(ToCypherHelper::relationToCypherString($relation, true, false));
// > [:TYPE {id: '123'}]

// alias to previous method
echo(ToCypherHelper::relationToIdentifyingCypherString($relation, false));
// > [:TYPE {id: '123'}]
```

## Escape helper

The escape helper is a function which can escape a single character from a string which is not already escaped.

```php
use \Syndesi\CypherDataStructures\Helper\EscapeHelper;

echo(EscapeHelper::escapeCharacter("'", "some string"));
// > some string

echo(EscapeHelper::escapeCharacter("'", "some 'string'"));
// > some \'string\'

echo(EscapeHelper::escapeCharacter("'", "some \\'string\\'"));
// > some \'string\'
```

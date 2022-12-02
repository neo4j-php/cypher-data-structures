# Helper

## To Cypher helper

A group of functions which are designed to take types from this library and convert them to Cypher-strings.

### Property storage to Cypher property string

Transforms objects of type `PropertyStorageInterface` to a Cypher property string with sorted names.  
Internal properties will be sorted first.

```php
use Syndesi\CypherDataStructures\Type\PropertyStorage;
use Syndesi\CypherDataStructures\Type\PropertyName;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

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
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;use Syndesi\CypherDataStructures\Type\NodeLabel;use Syndesi\CypherDataStructures\Type\NodeLabelStorage;

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
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;use Syndesi\CypherDataStructures\Type\Node;use Syndesi\CypherDataStructures\Type\NodeLabel;use Syndesi\CypherDataStructures\Type\PropertyName;

$node = new Node();
$node->addNodeLabel(new NodeLabel('Label'));
$node->addProperty(new PropertyName('id'), 1234);
$node->addProperty(new PropertyName('someKey'), 'some value');
$node->addIdentifier(new PropertyName('id'));

// print whole node
echo(ToCypherHelper::nodeToCypherString($node));
// > (:Label {id: '1234', someKey: 'some value'})

// print whole node with variable
echo(ToCypherHelper::nodeToCypherString($node, nodeVariable: 'node'));
// > (node:Label {id: '1234', someKey: 'some value'})

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
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;use Syndesi\CypherDataStructures\Type\Node;use Syndesi\CypherDataStructures\Type\NodeLabel;use Syndesi\CypherDataStructures\Type\PropertyName;use Syndesi\CypherDataStructures\Type\Relation;use Syndesi\CypherDataStructures\Type\RelationType;

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

// print relation with all properties & nodes with variable
echo(ToCypherHelper::relationToCypherString($relation, relationVariable: 'relation'));
// > (:StartNode {id: '1234'})-[relation:TYPE {id: '123', key: 'value'}]->(:EndNode {id: 4321})

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

### Constraint to cypher string

Transforms objects of type `ConstraintInterface` to a Cypher constraint string.

```php
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;use Syndesi\CypherDataStructures\Type\Constraint;use Syndesi\CypherDataStructures\Type\ConstraintName;use Syndesi\CypherDataStructures\Type\ConstraintType;use Syndesi\CypherDataStructures\Type\NodeLabel;use Syndesi\CypherDataStructures\Type\PropertyName;

$constraint = new Constraint();
$constraint
    ->setConstraintName(new ConstraintName('some_name'))
    ->setConstraintType(ConstraintType::UNIQUE)
    ->setFor(new NodeLabel('SomeNode'))
    ->addProperty(new PropertyName('id'));

echo(ToCypherHelper::constraintToCypherString($constraint));
// > CONSTRAINT some_name FOR (element:SomeNode) REQUIRE (element.id) IS UNIQUE
```

### Index to cypher string

Transforms objects of type `ConstraintInterface` to a Cypher constraint string.

```php
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;use Syndesi\CypherDataStructures\Type\Index;use Syndesi\CypherDataStructures\Type\IndexName;use Syndesi\CypherDataStructures\Type\IndexType;use Syndesi\CypherDataStructures\Type\NodeLabel;use Syndesi\CypherDataStructures\Type\PropertyName;

$index = new Index();
$index
    ->setIndexName(new IndexName('some_name'))
    ->setIndexType(IndexType::BTREE)
    ->setFor(new NodeLabel('SomeNode'))
    ->addProperty(new PropertyName('id'));

echo(ToCypherHelper::indexToCypherString($index));
// > BTREE INDEX some_name FOR (element:SomeNode) ON (element.id)
```

### Option storage to Cypher string

Transforms objects of type `OptionStorageInterface` to a Cypher string with sorted keys. Values must be scalar, array or
implement `Stringable`.

```php
use Syndesi\CypherDataStructures\Type\OptionStorage;
use Syndesi\CypherDataStructures\Type\OptionName;
use Syndesi\CypherDataStructures\Helper\ToCypherHelper;

$indexConfig = new OptionStorage();
$indexConfig->attach(new OptionName('spatial.cartesian.min', [-100.0, -100.0]));
$indexConfig->attach(new OptionName('spatial.cartesian.max', [100.0, 100.0]));
$optionStorage = new OptionStorage();
$optionStorage->attach(new OptionName('indexConfig'), $indexConfig);

echo(ToCypherHelper::optionStorageToCypherString($optionStorage));
// > {indexConfig: {`spatial.cartesian.max`: [100, 100], `spatial.cartesian.min`: [-100, -100]}}
```

## Escape helper

The escape helper is a function which can escape a single character from a string which is not already escaped.

```php
use Syndesi\CypherDataStructures\Helper\EscapeHelper;

echo(EscapeHelper::escapeCharacter("'", "some string"));
// > some string

echo(EscapeHelper::escapeCharacter("'", "some 'string'"));
// > some \'string\'

echo(EscapeHelper::escapeCharacter("'", "some \\'string\\'"));
// > some \'string\'
```

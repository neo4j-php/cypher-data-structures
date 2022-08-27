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
// > _internal: 'value _', propertyA: 'value A', propertyB: 'value B', propertyZ: 'property Z'
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

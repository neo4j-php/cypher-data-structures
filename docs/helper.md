# Helper

## To Cypher helper

A group of functions which are designed to take types from this library and convert them to Cypher-strings.

### Property storage to Cypher property string

Transforms objects of type `PropertyStorageInterface` to a Cypher property string with sorted keys.  
Internal properties will be sorted first.

```php
// todo
```

## Escape helper

The escape helper is a function which can escape a single character from a string which is not already escaped.

```php
use \Syndesi\CypherDataStructures\Helper\EscapeHelper;

echo(EscapeHelper::escapeCharacter("'", "some string"));  // prints "some string"
echo(EscapeHelper::escapeCharacter("'", "some 'string'"));  // prints "some \'string\'"
```

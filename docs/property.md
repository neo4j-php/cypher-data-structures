# Property

Properties are defined as three separate types:

- **PropertyName**: The name of the property, basically a string with validation. It must be camelCase as per [Neo4j's
  documentation](https://neo4j.com/docs/cypher-manual/current/styleguide/#cypher-styleguide-casing).  
  Property names can start with a single underscore, although this is reserved for internal logic.  
  You can overwrite the validation part by creating your own implementation of
  `Syndesi\CypherDataStructures\Contract\PropertyNameInterface`.

- **PropertyValue**: This library does not provide a specific type, it uses `mixed` internally.  
  Check out [Neo4j's official documentation regarding property
  types](https://neo4j.com/docs/cypher-manual/current/syntax/values/#property-types) as well as the [supported types in
  Laudis' Neo4j client library](https://github.com/neo4j-php/neo4j-php-client#accessing-the-results) for real world
  limitations.

- **PropertyStorage**: Basically an array which supports `PropertyName` as keys and `mixed` for values. Uses
  [SplObjectStorage](https://www.php.net/manual/en/class.splobjectstorage) internally.

## Examples

```php
use \Syndesi\CypherDataStructures\Type\PropertyName;
use \Syndesi\CypherDataStructures\Type\PropertyStorage;

// create property names
$somePropertyName = new PropertyName("somePropertyName");
$anotherPropertyName = new PropertyName("anotherPropertyName");

// create array-like property storage
$propertyStorage = new PropertyStorage();

// add properties to the property storage
$propertyStorage->attach($somePropertyName, 'some property value');
$propertyStorage->attach($anotherPropertyName, 'another property value');

// get property from property storage
$value = $propertyStorage->offsetGet(new PropertyName("somePropertyName"));
```

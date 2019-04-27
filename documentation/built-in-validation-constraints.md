# Built-in validation constraints

## Table of contents

1. [Presence constraints](#c-1)
    1. [isPresent()](#c-1-1)
    2. [notPresent()](#c-1-2)
    3. [isOptional($defaultValue = null)](#c-1-3)
    4. [isEmpty()](#c-1-4)
    5. [notEmpty()](#c-1-5)
    6. [isDefined()](#c-1-6)
2. [String constraints](#c-2)
    1. [isBlank()](#c-2-1)
    2. [notBlank()](#c-2-2)
    3. [matches($regex)](#c-2-3)
3. [Type constraints](#c-3)
    1. [isString()](#c-3-1)
    2. [isInt()](#c-3-2)
    3. [isFloat()](#c-3-3)
    4. [isNumber()](#c-3-4)
    5. [isBoolean()](#c-3-5)
    6. [isArray()](#c-3-6)
    7. [isObject()](#c-3-7)
4. [Comparison constraints](#c-4)
    1. [is( '==', $anotherValue )](#c-4-1)
    2. [is( '!=', $anotherValue )](#c-4-2)
    3. [is( '>', $anotherValue )](#c-4-3)
    4. [is( '>=', $anotherValue )](#c-4-4)
    5. [is( '<', $anotherValue )](#c-4-5)
    6. [is( '<=', $anotherValue )](#c-4-6)
    7. [is( '~', $anotherValue, $precision )](#c-4-7)
    8. [is( '!~', $anotherValue, $precision )](#c-4-8)
    9. [length( $min, $max )](#c-4-9)
5. [Inclusion contraints](#c-5)
    1. [isIn( $valuesCollection )](#c-5-1)
    2. [notIn( $valuesCollection )](#c-5-2)
    3. [has( $anotherValue )](#c-5-2)
    4. [hasNot( $anotherValue )](#c-5-3)
    5. [hasAll( $valuesCollection )](#c-5-4)
    6. [hasAny( $valuesCollection )](#c-5-5)
    7. [hasNone( $valuesCollection )](#c-5-6)
6. [Converters](#c-6)
    1. [asString()](#c-6-1)
    2. [asInt()](#c-6-2)
    3. [asFloat()](#c-6-3)
    4. [asBoolean](#c-6-4)

<a name="c-1"></a>
## Presence constraints

<a name="c-1-1"></a>
### isPresent()

Fails if the value is null.

Usage

```php
$object->isPresent()
```

Halts on failure.

<a name="c-1-2"></a>
### notPresent()

Fails if the value is not null.

Usage

```php
$object->notPresent()
```

Halts on failure.

<a name="c-1-3"></a>
### isOptional($defaultValue = null)

If no $defaultValue is given halts if the validated value is null.

If a $defaultValue is given and the validated value is null sets the $defaultValue.

It does not add any ValidationError in any case.

Usage

```php
$object->isOptional()

$object->isOptional( 10 )
```

<a name="c-1-4"></a>
### isEmpty()

Fails if the array value is not empty.

Usage

```php
$object->isEmpty()
```

<a name="c-1-5"></a>
### notEmpty()

Fails if the array value is empty.

Usage

```php
$object->notEmpty()
```

<a name="c-1-6"></a>
### isDefined()

Fails if the value is null or is blank.

Usage

```php
$object->isDefined()
```

Halts on failure.

<a name="c-2"></a>
## String constraints

<a name="c-2-1"></a>
### isBlank()

Fails if the value is not blank.

A blank string is empty or composed by any number of spaces, tabs and/or carriage returns.

Usage

```php
$object->isBlank()
```

<a name="c-2-2"></a>
### notBlank()

Fails if the value is blank.

A blank string is empty or composed by any number of spaces, tabs and/or carriage returns.

Usage

```php
$object->notBlank()
```

<a name="c-2-3"></a>
### matches($regex)

Fails if the value does not match a regular expression.

Usage

```php
$object->matches( '/.*/' )
```

<a name="c-3"></a>
## Type constraints

<a name="c-3-1"></a>
### isString()

Fails if the value is not a string.

Usage

```php
$object->isString()
```

Halts on failure.

<a name="c-3-2"></a>
### isInt()

Fails if the value is not an integer.

Usage

```php
$object->isInt()
```

Halts on failure.

<a name="c-3-3"></a>
### isFloat()

Fails if the value is not a float.

Usage

```php
$object->isFloat()
```

Halts on failure.

<a name="c-3-4"></a>
### isNumber()

Fails if the value is not an integer or a float.

Usage

```php
$object->isNumber()
```

Halts on failure.

<a name="c-3-5"></a>
### isBoolean()

Fails if the value is not a boolean.

Usage

```php
$object->isBoolean()
```

Halts on failure.

<a name="c-3-6"></a>
### isArray()

Fails if the value is not an array.

Usage

```php
$object->isArray()
```

Halts on failure.

<a name="c-3-7"></a>
### isObject()

Fails if the value is not an object.

Usage

```php
$object->isObject()
```

Halts on failure.

<a name="c-4"></a>
## Comparison constraints

<a name="c-4-1"></a>
### is( '==', $anotherValue )

Fails if the value is not == to another value.

Usage

```php
$object->is( '==', 1 )
```

<a name="c-4-2"></a>
### is( '!=', $anotherValue )

Fails if the value is not != to another value.

Usage

```php
$object->is( '!=', 1 )
```

<a name="c-4-3"></a>
### is( '>', $anotherValue )

Fails if the value is not > to another value.

Usage

```php
$object->is( '>', 1 )
```

<a name="c-4-4"></a>
### is( '>=', $anotherValue )

Fails if the value is not >= to another value.

Usage

```php
$object->is( '>=', 1 )
```

<a name="c-4-5"></a>
### is( '<', $anotherValue )

Fails if the value is not < to another value.

Usage

```php
$object->is( '<', 1 )
```

<a name="c-4-6"></a>
### is( '<=', $anotherValue )

Fails if the value is not <= to another value.

Usage

```php
$object->is( '<=', 1 )
```

<a name="c-4-7"></a>
### is( '~', $anotherValue, $precision )

Fails if the value is not aproximatly equal to another value with a given precision.

To check for equality is used `abs( value - $anotherValue ) <= $precision`.

Usage

```php
$object->is( '~', 10.00, 0.01 )
```

<a name="c-4-8"></a>
### is( '!~', $anotherValue, $precision )

Fails if the value is aproximatly equal to another value with a given precision.

To check for equality is used `abs( value - $anotherValue ) <= $precision`.

Usage

```php
$object->is( '!~', 10.00, 0.01 )
```

<a name="c-4-9"></a>
### length( $min, $max )

For strings and arrays.

Fails if the value length is not in a given range.

Usage

```php
// Value length must be between 1 and 10
$object->length( 1, 10 )

// Value length must be <= 10
$object->length( null, 10 )

// Value length must be >= 0
$object->length( 0, null )
```

<a name="c-5"></a>
## Inclusion contraints

<a name="c-5-1"></a>
### isIn( $valuesCollection )

Fails if the value is not included in the collection.

Usage

```php
$object->isIn( [ 'active', 'inactive' ] )
```

<a name="c-5-2"></a>
### notIn( $valuesCollection )

Fails if the value is included in the collection.

Usage

```php
$object->notIn( [ 'active', 'inactive' ] )
```

<a name="c-5-3"></a>
### has( $anotherValue )

For array values only.

Fails if the value does not include a constant.

Usage

```php
$object->has( 0 )
```

<a name="c-5-4"></a>
### hasNot( $anotherValue )

For array values only.

Fails if the value includes a constant.

Usage

```php
$object->hasNot( 0 )
```

<a name="c-5-5"></a>
### hasAll( $valuesCollection )

For array values only.

Fails if the value does not include all the values in a valuesCollection.

Usage

```php
$object->hasAll( [ 1, 2, 3 ] )
```

<a name="c-5-6"></a>
### hasAny( $valuesCollection )

For array values only.

Fails if the value does not include any the values in a valuesCollection.

Usage

```php
$object->hasAny( [ 1, 2, 3 ] )
```

<a name="c-5-7"></a>
### hasNone( $valuesCollection )

For array values only.

Fails if the value includes any of the values in a valuesCollection.

Usage

```php
$object->hasNone( [ 1, 2, 3 ] )
```

<a name="c-6"></a>
## Converters

<a name="c-6-1"></a>
### asString()

Converts the validated value to a string.

Convertion is done using `(string)` cast.

Usage

```php
$object->asString()
```

Subsequent validations will use the converted value.

<a name="c-6-2"></a>
### asInt()

Converts the validated value to an integer.

Convertion is done using `(int)` cast.

Usage

```php
$object->asInt()
```

Subsequent validations will use the converted value.

<a name="c-6-3"></a>
### asFloat()

Converts the validated value to a float.

Convertion is done using `(float)` cast.

Usage

```php
$object->asFloat()
```

Subsequent validations will use the converted value.

<a name="c-6-4"></a>
### asBoolean

Converts the validated value to a boolean.

Convertion is done using `(boolean)` cast.

Usage

```php
$object->asBoolean()
```

Subsequent validations will use the converted value.
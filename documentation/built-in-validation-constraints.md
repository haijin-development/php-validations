# Built-in validation constraints

## Table of contents

1. [Presence constraints](#c-1)
    1. [is_present()](#c-1-1)
    2. [not_present()](#c-1-2)
    3. [is_optional($default_value = null)](#c-1-3)
    4. [is_empty()](#c-1-4)
    5. [not_empty()](#c-1-5)
    6. [is_defined()](#c-1-6)
2. [String constraints](#c-2)
    1. [is_blank()](#c-2-1)
    2. [not_blank()](#c-2-2)
    3. [matches($regex)](#c-2-3)
3. [Type constraints](#c-3)
    1. [is_string()](#c-3-1)
    2. [is_int()](#c-3-2)
    3. [is_float()](#c-3-3)
    4. [is_number()](#c-3-4)
    5. [is_boolean()](#c-3-5)
    6. [is_array()](#c-3-6)
    7. [is_object()](#c-3-7)
4. [Comparison constraints](#c-4)
    1. [is( '==', $another_value )](#c-4-1)
    2. [is( '!=', $another_value )](#c-4-2)
    3. [is( '>', $another_value )](#c-4-3)
    4. [is( '>=', $another_value )](#c-4-4)
    5. [is( '<', $another_value )](#c-4-5)
    6. [is( '<=', $another_value )](#c-4-6)
    7. [is( '~', $another_value, $precision )](#c-4-7)
    8. [is( '!~', $another_value, $precision )](#c-4-8)
    9. [length( $min, $max )](#c-4-9)
5. [Inclusion contraints](#c-5)
    1. [is_in( $values_collection )](#c-5-1)
    2. [not_in( $values_collection )](#c-5-2)
    3. [has( $another_value )](#c-5-2)
    4. [has_not( $another_value )](#c-5-3)
    5. [has_all( $values_collection )](#c-5-4)
    6. [has_any( $values_collection )](#c-5-5)
    7. [has_none( $values_collection )](#c-5-6)
6. [Converters](#c-6)
    1. [to_string()](#c-6-1)
    2. [as_int()](#c-6-2)
    3. [as_float()](#c-6-3)
    4. [as_boolean](#c-6-4)

<a name="c-1"></a>
## Presence constraints

<a name="c-1-1"></a>
### is_present()

Fails if the value is null.

Usage

```php
$object->is_present()
```

Halts on failure.

<a name="c-1-2"></a>
### not_present()

Fails if the value is not null.

Usage

```php
$object->not_present()
```

Halts on failure.

<a name="c-1-3"></a>
### is_optional($default_value = null)

If no $default_value is given halts if the validated value is null.

If a $default_value is given and the validated value is null sets the $default_value.

It does not add any Validation_Error in any case.

Usage

```php
$object->is_optional()

$object->is_optional( 10 )
```

<a name="c-1-4"></a>
### is_empty()

Fails if the array value is not empty.

Usage

```php
$object->is_empty()
```

<a name="c-1-5"></a>
### not_empty()

Fails if the array value is empty.

Usage

```php
$object->not_empty()
```

<a name="c-1-6"></a>
### is_defined()

Fails if the value is null or is blank.

Usage

```php
$object->is_defined()
```

Halts on failure.

<a name="c-2"></a>
## String constraints

<a name="c-2-1"></a>
### is_blank()

Fails if the value is not blank.

A blank string is empty or composed by any number of spaces, tabs and/or carriage returns.

Usage

```php
$object->is_blank()
```

<a name="c-2-2"></a>
### not_blank()

Fails if the value is blank.

A blank string is empty or composed by any number of spaces, tabs and/or carriage returns.

Usage

```php
$object->not_blank()
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
### is_string()

Fails if the value is not a string.

Usage

```php
$object->is_string()
```

Halts on failure.

<a name="c-3-2"></a>
### is_int()

Fails if the value is not an integer.

Usage

```php
$object->is_int()
```

Halts on failure.

<a name="c-3-3"></a>
### is_float()

Fails if the value is not a float.

Usage

```php
$object->is_float()
```

Halts on failure.

<a name="c-3-4"></a>
### is_number()

Fails if the value is not an integer or a float.

Usage

```php
$object->is_number()
```

Halts on failure.

<a name="c-3-5"></a>
### is_boolean()

Fails if the value is not a boolean.

Usage

```php
$object->is_boolean()
```

Halts on failure.

<a name="c-3-6"></a>
### is_array()

Fails if the value is not an array.

Usage

```php
$object->is_array()
```

Halts on failure.

<a name="c-3-7"></a>
### is_object()

Fails if the value is not an object.

Usage

```php
$object->is_object()
```

Halts on failure.

<a name="c-4"></a>
## Comparison constraints

<a name="c-4-1"></a>
### is( '==', $another_value )

Fails if the value is not == to another value.

Usage

```php
$object->is( '==', 1 )
```

<a name="c-4-2"></a>
### is( '!=', $another_value )

Fails if the value is not != to another value.

Usage

```php
$object->is( '!=', 1 )
```

<a name="c-4-3"></a>
### is( '>', $another_value )

Fails if the value is not > to another value.

Usage

```php
$object->is( '>', 1 )
```

<a name="c-4-4"></a>
### is( '>=', $another_value )

Fails if the value is not >= to another value.

Usage

```php
$object->is( '>=', 1 )
```

<a name="c-4-5"></a>
### is( '<', $another_value )

Fails if the value is not < to another value.

Usage

```php
$object->is( '<', 1 )
```

<a name="c-4-6"></a>
### is( '<=', $another_value )

Fails if the value is not <= to another value.

Usage

```php
$object->is( '<=', 1 )
```

<a name="c-4-7"></a>
### is( '~', $another_value, $precision )

Fails if the value is not aproximatly equal to another value with a given precision.

To check for equality is used `abs( value - $another_value ) <= $precision`.

Usage

```php
$object->is( '~', 10.00, 0.01 )
```

<a name="c-4-8"></a>
### is( '!~', $another_value, $precision )

Fails if the value is aproximatly equal to another value with a given precision.

To check for equality is used `abs( value - $another_value ) <= $precision`.

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
### is_in( $values_collection )

Fails if the value is not included in the collection.

Usage

```php
$object->is_in( [ 'active', 'inactive' ] )
```

<a name="c-5-2"></a>
### not_in( $values_collection )

Fails if the value is included in the collection.

Usage

```php
$object->not_in( [ 'active', 'inactive' ] )
```

<a name="c-5-3"></a>
### has( $another_value )

For array values only.

Fails if the value does not include a constant.

Usage

```php
$object->has( 0 )
```

<a name="c-5-4"></a>
### has_not( $another_value )

For array values only.

Fails if the value includes a constant.

Usage

```php
$object->has_not( 0 )
```

<a name="c-5-5"></a>
### has_all( $values_collection )

For array values only.

Fails if the value does not include all the values in a values_collection.

Usage

```php
$object->has_all( [ 1, 2, 3 ] )
```

<a name="c-5-6"></a>
### has_any( $values_collection )

For array values only.

Fails if the value does not include any the values in a values_collection.

Usage

```php
$object->has_any( [ 1, 2, 3 ] )
```

<a name="c-5-7"></a>
### has_none( $values_collection )

For array values only.

Fails if the value includes any of the values in a values_collection.

Usage

```php
$object->has_none( [ 1, 2, 3 ] )
```

<a name="c-6"></a>
## Converters

<a name="c-6-1"></a>
### to_string()

Converts the validated value to a string.

Convertion is done using `(string)` cast.

Usage

```php
$object->to_string()
```

Subsequent validations will use the converted value.

<a name="c-6-2"></a>
### as_int()

Converts the validated value to an integer.

Convertion is done using `(int)` cast.

Usage

```php
$object->as_int()
```

Subsequent validations will use the converted value.

<a name="c-6-3"></a>
### as_float()

Converts the validated value to a float.

Convertion is done using `(float)` cast.

Usage

```php
$object->as_float()
```

Subsequent validations will use the converted value.

<a name="c-6-4"></a>
### as_boolean

Converts the validated value to a boolean.

Convertion is done using `(boolean)` cast.

Usage

```php
$object->as_boolean()
```

Subsequent validations will use the converted value.
# Haijin Validations

Framework to easily validate objects and attributes using a simple and expressive DSL.

[![Latest Stable Version](https://poser.pugx.org/haijin/validations/version)](https://packagist.org/packages/haijin/validations)
[![Latest Unstable Version](https://poser.pugx.org/haijin/validations/v/unstable)](https://packagist.org/packages/haijin/validations)
[![Build Status](https://travis-ci.org/haijin-development/php-validations.svg?branch=v0.0.2)](https://travis-ci.org/haijin-development/php-validations)
[![License](https://poser.pugx.org/haijin/validations/license)](https://packagist.org/packages/haijin/validations)

### Version 0.0.2

This library is under active development and no stable version was released yet.

If you like it a lot you may contribute by [financing](https://github.com/haijin-development/support-haijin-development) its development.

## Table of contents

1. [Installation](#c-1)
2. [Usage](#c-2)
    1. [Using a Validator object](#c-2-1)
    2. [Custom validations](#c-2-2)
    3. [Custom validation with parameters](#c-2-3)
    4. [Custom validations with closures](#c-2-4)
    5. [Validating array items](#c-2-5)
    6. [Halting validations](#c-2-6)
    7. [Custom validator example](#c-2-7)
3. [Built-in validation constraints](#c-3)
4. [Converters](#c-4)
5. [Adding and overriding built-in validations and converters](#c-5)
6. [Integrating the validations in applications](#c-6)
7. [Validation messages](#c-7)
    1. [Create a new ValidationErrorsDictionary](#c-7-1)
    2. [Define a default validation message](#c-7-2)
    3. [Define more specific messages](#c-7-3)
8. [Running the tests](#c-8)

<a name="c-1"></a>
## Installation

Include this library in your project `composer.json` file:

```json
{
    ...

    "require": {
        ...
        "haijin/validations": "^0.0.2",
        ...
    },

    ...
}
```

<a name="c-2"></a>
## Usage

[Code examples](./documentation/validation-examples.php).

<a name="c-2-1"></a>
### Using a Validator object

Given an object like:

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];
```

Create a Validator instance and call `validate` on the object:

```php
$validator = new Validator();

$validator->validate( $user, function($user) {
    $user ->is_present();

    $user->attr('name') ->is_defined() ->is_string();

    $user->attr('last_name') ->is_defined() ->is_string();

    $user->attr('address') ->is_defined() ->is_array() ->eval( function($address) {

        $address->attr('street') ->is_defined() ->is_string();

        $address->attr('number') ->is_defined() ->is_string();
    });
});

$validation_errors = $validator->get_errors();
```

Using `[]` also works:

```php
$validator = new Validator();

$validator->validate( $user, function($user) {
    $user ->is_present();

    $user['name'] ->is_defined() ->is_string();

    $user['last_name'] ->is_defined() ->is_string();

    $user['address'] ->is_defined() ->is_array() ->eval( function($address) {

        $address['street'] ->is_defined() ->is_string();

        $address['number'] ->is_defined() ->is_string();
    });
});

$validation_errors = $validator->get_errors();
```


<a name="c-2-2"></a>
### Custom validations

Reuse frequent validations in a Validator subclass and use it in the object validation:

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$validator = new Validator();

$validation_errors = $validator->validate( $user, function($user) {
    $user ->is_present();

    $user->attr('name') ->is_defined() ->is_string();

    $user->attr('last_name') ->is_defined() ->is_string();

    $user->attr('address') ->is_defined() ->validate_with( 'AddressValidator' );
});

// Where AddressValiador is a custom Validator subclass:

class AddressValidator extends Validator
{
    public function evaluate()
    {
        $this->is_array();

        $this->attr( 'street', function($street) {
            $street ->is_defined() ->is_string();
        });

        $this->attr( 'number', function($number) {
            $number ->is_defined() ->is_string();
        });
    }
}
```

<a name="c-2-3"></a>
### Custom validation with parameters

Pass the Validator subclass instance instead of its name to parametrize it:

```php
$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$validator = new Validator();

$validation_errors = $validator->validate( $user, function($user) {
    $user ->is_present();

    $user->attr('name') ->is_defined() ->is_string();

    $user->attr('last_name') ->is_defined() ->is_string();

    $user->attr('address') ->is_defined() ->validate_with( new ConfigurableAddressValidator( 30 ) );
});

// Where ConfigurableAddressValidator is a custom Validator subclass:

class ConfigurableAddressValidator extends Validator
{
    public function __construct($max_length = 255)
    {
        parent::__construct();

        $this->max_length = $max_length;
    }

    public function evaluate()
    {
        $this->is_array();

        $this->attr('street') ->is_defined() ->is_string() ->length( 0, $this->max_length );

        $this->attr('number') ->is_defined() ->is_string() ->length( 0, $this->max_length );
    }
}


```

<a name="c-2-4"></a>
### Custom validations with closures

Perform simple validations using a closure:

```php
$n = 1;

$validator = new Validator();

$validation_errors = $validator->validate( $n, function($n) {
    $n ->is_defined() ->is_int();

    $n->validate_with( function($validator) {
        $validator->set_validation_name( 'is_odd' );

        if( $this->get_value() % 2 == 0 ){
            $validator->add_error();            
        }         
    });
});
```

<a name="c-2-5"></a>
### Validating array items

Validate items in arrays with:

```php
$numbers = [ 1, 3, 5, 7 ];

$validator = new Validator();

$validation_errors = $validator->validate( $numbers, function($numbers) {
    $numbers ->is_defined() ->is_array();

    $numbers->each( function($n) {

        $n ->is_defined() ->is_int() ->is( '>', 0 );

    });
});
```

### Writting complex validations
 
Some validations involve accessing multiple attributes, invoking other validations and doing calculations. In those cases write a custom Validator subclass and use its available protocol.

```php
class CustomValidator extends Validator
{
    public function evaluate()
    {
        /// Available functions during validations


        // Define the validation name
        $this->set_validation_name( 'complex-validation' );

        // Get the validation name
        $this->get_validation_name();

        // Define the validation name
        $this->set_validation_parameters( [ 1, 2, 3 ] );

        // Get the validation parameters
        $this->get_validation_parameters();

        // Get the validated value
        $this->get_value();

        // Get the value of some other attribute
        $this->get_value_at( 'address.street' );

        // Get the value of some other attribute that is included in an array
        $this->get_value_at( 'items.[3].description' );

        // Get the attribute path
        $this->get_attribute_path();

        // Override the attribute path
        $this->set_attribute_path( 'address.street' );

        // Get the validation errors previously collected
        $this->get_errors();

        // Add a validation error using the current validation_name and default values
        $this->add_error();

        // Add a validation error overriding some or all of the default values. Note that is has
        // only para parameters, an associative array, like in the js way.
        $this->add_error([
            'value' => 'overriden validated value',
            'attribute_path' => 'overriden.attribute',
            'validation_name' => 'overriden-validation-name',
            'validation_parameters' => [ 'overriden', 'validation', 'parameters' ] 
        ]);

        // Add errors collected from another validation
        $this->add_all_errors( $errors );

        // Halt validations and do not validate this attribute children
        $this->halt();

        // Validate a child attribute
        $this->attr('address') ->is_present();

        // Validate a child attribute with a custom block
        $this->attr('address') ->is_present() ->eval( function($address) {
            $address ->is_object();
        });

        // This also works
        $this->attr('address', function($address) {
            $address ->is_present() ->is_object();
        });
    }
}
```

<a name="c-2-6"></a>
### Halting validations

Validations may halt or not subsequent validations.

To avoid further validations on an attribute branch call `halt()`.

When halting a validation the validation on its children are not run, but the do continue on its siblings.

Here's an example:

```php
$user = [
    'name' => null,
    'last_name' => 'Simpson',
    'address' => null
];

$validator = new Validator();

$validation_errors = $validator->validate( $user, function($user) {
    $user ->is_defined();

    $user->attr( 'name', function($name) {
        // The is_defined validation fails because name is null and halts. is_string is not called.
        $name ->is_defined() ->is_string();
    });

    // These validations are still run
    $user->attr( 'last_name', function($last_name) {
        $last_name ->is_defined() ->is_string();
    });

    $user->attr( 'address', function($address) {
        // The is_defined validation fails because address is null and halts
        $address->is_defined();

        // These child validations are not run
        $address->attr( 'street' ) ->is_defined();
        $address->attr( 'number' ) ->is_defined();
    });
});
```

<a name="c-2-7"></a>
### Custom validator example

This Validator validates a purchase object:

```php
class PurchaseValidator extends Validator
{
    /**
     * Validates a purchase object.
     */
    public function evaluate()
    {
        $this->attr( 'date' ) ->is_defined();

        $this->attr( 'items' ) ->is_defined() ->is_array() ->each( function($each_item){
            $each_item ->is_defined();

            $each_item->attr( 'description' ) ->is_defined() ->is_string();
            $each_item->attr( 'price' ) ->is_defined() ->is_number() ->is( '>=', 0 );
        }) ;

        $this->attr( 'total' ) ->is_defined() ->is_number() ->is( '>=', 0 );

        // Extracted to a method on its own because of its complexity.
        // Only run this validation if there are no previous errors in the purchase.
        if( count( $this->get_errors() ) == 0 )
            $this->validate_total_sum();
    }

    /**
     * Validates that the sum of all item prices equals the purchase total.
     */
    protected function validate_total_sum()
    {
        $this->set_validation_name( 'total-sum' );

        $total = $this->get_value_at( 'total' );

        $sum = 0;
        foreach( $this->get_value_at( 'items' ) as $item ) {
            $sum += $item[ 'price' ];
        }

        if( $total != $sum ) {
            $this->add_error();
        }
    }
}


$purchase = new stdclass();
$purchase->date = new DateTime();
$purchase->items = [
        [
            'description' => 'item 1',
            'price' => 3.00
        ],
        [
            'description' => 'item 2',
            'price' => 4.00
        ],
    ];
$purchase->total = 7.00;


// Validate the $purchase object

$validator = new Validator();

$validation_errors = $validator->validate( $purchase, function($purchase) {
    $purchase ->is_defined() ->is_object() ->validate_with( 'PurchaseValidator' );
});
```

<a name="c-3"></a>
## Built-in validation constraints

[Built-in constraints and converters](./documentation/built-in-validation-constraints.md)

<a name="c-4"></a>
## Converters

Convert a value before validating it with:

```php
$validator = new Validator();

$validation_errors = $validator->validate( '1', function($n) {
    $n ->is_defined() ->is_string() ->as_int() ->is( '>', 0 );
});
```

Write a custom converter with:

```php
class IncrementConverter extends Validator
{
    public function evaluate()
    {
        $value = $this->get_value();

        $this->set_value( $value + 1 );
    }
}
```

<a name="c-5"></a>
## Adding and overriding built-in validations and converters

Override and add built in validations defining methods in a Validator subclass and using that subclass instead of Validator:
 
```php
class ExtendedValidator extends Validator
{
    public function is_address()
    {
        $this->attr( 'street', function($street) {
            $street ->is_defined() ->is_string();
        });

        $this->attr( 'number', function($number) {
            $number ->is_defined() ->is_string();
        });
    }
}

$validator = new ExtendedValidator();

$validation_errors = $validator->validate( $user, function($user) {
    $user ->is_defined();

    $user->attr( 'name' ) ->is_defined() ->is_string();

    $user->attr( 'last_name' ) ->is_defined() ->is_string();

    $user->attr( 'address' ) ->is_address();
});
```

<a name="c-6"></a>
## Integrating the validations in applications

Example that validates an object before storing it in a database.

Integrate the validations like this:

```php
abstract class PersistentCollection
{
    public function save($object)
    {
        $validation_errors = $this->validate_before_saving( $object );

        if( count( $validation_errors ) > 0 )
            return $validation_errors;

        $this->do_save( $object );
    }

    public function do_save($object)
    {
        // actual saving here ...
    }

    protected function validate_before_saving($object)
    {
        $validator = new Haijin\Validations\Validator;
        $validator->set_binding( $this );

        return $validator->validate( $object, function($object) {
            $this->validate( $object );
        });
    }

    abstract protected function validate($validator);
}

class UserPersistenCollection extends PersistentCollection
{
    protected function validate($user)
    {
        $user->attr( 'name', function($name){
            $name ->is_defined() ->is_string() ->length( 1, 255 );
        });

        $user->attr( 'last_name', function($last_name) {
            $last_name ->is_defined() ->is_string() ->length( 1, 255 );
        });

        // etc ...
    }
}

// And then use it like this:

$user = [
    'name' => 123,
    'last_name' => null,
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$persistent_collection = new UserPersistenCollection();
$validation_errors = $persistent_collection->save( $user );

var_dump( $validation_errors );
```

<a name="c-7"></a>
## Validation messages

The validation message is not part of the ValidationError object. Validation messages are handled in a definition of their own.

This allows to conveniently define and override the messages to the end users in any part of the application.

[Code example of defining validation messages](./documentation/validation-message-example.php).

<a name="c-7-1"></a>
### Create a new ValidationErrorsDictionary

Use a dictionary with default messages

```php
$errors_dictionary = ValidationErrorsDictionary::new_default();
```

or create a new one

```php
$errors_dictionary = new ValidationErrorsDictionary();
```

<a name="c-7-2"></a>
### Define a default validation message

```php
$errors_dictionary->define( function($messages) {

    $messages->default( function($validation_error) {
        return "The attribute '{$validation_error->get_attribute_name()}' is not valid.";
    });

});
```

Get the validations messages

```php
foreach( $validation_errors as $error ) {
    $message = $errors_dictionary->message_for( $error );
    print( $message );
    print "\n";
}
```

<a name="c-7-3"></a>
### Define more specific messages

```php
$errors_dictionary->define( function($messages) {

    $messages->at_validation( 'not_blank', function($validation_error) {
        return "The attribute '{$validation_error->get_attribute_name()}' must contain visible characters.";
    });

    $messages->at_validation( 'length', function($validation_error) {
        $params = $validation_error->get_validation_parameters();

        return "The attribute '{$validation_error->get_attribute_name()}' length must be in the range ({$params[0]}, {$params[1]})";
    });
});
```

Get the validations messages

```php
foreach( $validation_errors as $error ) {
    print( $errors_dictionary->message_for( $error ) );
    print "\n";
}
```

<a name="c-8"></a>
## Running the tests

```
composer test
```
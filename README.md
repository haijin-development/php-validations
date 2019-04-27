# Haijin Validations

Framework to easily validate objects and attributes using a simple and expressive DSL.

[![Latest Stable Version](https://poser.pugx.org/haijin/validations/version)](https://packagist.org/packages/haijin/validations)
[![Latest Unstable Version](https://poser.pugx.org/haijin/validations/v/unstable)](https://packagist.org/packages/haijin/validations)
[![Build Status](https://travis-ci.org/haijin-development/php-validations.svg?branch=master)](https://travis-ci.org/haijin-development/php-validations)
[![License](https://poser.pugx.org/haijin/validations/license)](https://packagist.org/packages/haijin/validations)

**Highlights**

* Zero configuration. 
* Defines complex validation of nested attributes with an expressive DSL in plain PHP.
* Custom validations with classes, closures or callables.
* Completely separates the validation errors from the validation error messages.
* Allows to define and see the complete validation for a request or object at one glance.

### Version 2.0.0

If you like it a lot you may contribute by [financing](https://github.com/haijin-development/support-haijin-development) its development.

## Table of contents

1. [Installation](#c-1)
2. [Usage](#c-2)
    1. [Using a Validator object](#c-2-1)
    2. [Custom validations](#c-2-2)
    3. [Custom validation with parameters](#c-2-3)
    4. [Custom validations with callables](#c-2-4)
    5. [Validating arrays](#c-2-5)
    6. [Halting validations](#c-2-6)
    7. [Custom validator example](#c-2-7)
3. [Built-in validation constraints](#c-3)
4. [Converters](#c-4)
5. [Adding and overriding built-in validations and converters](#c-5)
6. [Integrating the validations in applications](#c-6)
7. [Validation messages](#c-7)
    1. [Create a new ValidationMessagesDictionary](#c-7-1)
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
        "haijin/validations": "^2.0",
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

Given the object:

```php
$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];
```

Create a Validator instance and call `validate` on the `$user` object:

```php
use Haijin\Validations\Validator;

$validator = new Validator();

$validationErrors = $validator->validate( $user, function($user) {

    $user ->isPresent();

    $user->attr('name') ->isDefined() ->isString();

    $user->attr('lastName') ->isDefined() ->isString();

    $user->attr('address') ->isDefined() ->isArray() ->eval( function($address) {

        $address->attr('street') ->isDefined() ->isString();

        $address->attr('number') ->isDefined() ->isString();

    });

});

// or get the validation errors with
$validationErrors = $validator->getErrors();
```

<a name="c-2-2"></a>
### Custom validations

Reuse frequent validations in a CustomValidator subclass and use it in the object validation:

```php
use Haijin\Validations\CustomValidator;

class Address_Validator extends CustomValidator
{
    public function evaluate()
    {
        $this->isArray();

        $this->attr( 'street', function($street) {
            $street ->isDefined() ->isString();
        });

        $this->attr( 'number', function($number) {
            $number ->isDefined() ->isString();
        });
    }
}

// and use it like this:

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$validator = new Validator();

$validationErrors = $validator->validate( $user, function($user) {

    $user ->isPresent();

    $user->attr('name') ->isDefined() ->isString();

    $user->attr('lastName') ->isDefined() ->isString();

    $user->attr('address') ->isDefined() ->validateWith( Address_Validator::class );

});
```

<a name="c-2-3"></a>
### Custom validation with parameters

Pass a CustomValidator instance instead of its name to parametrize it:

```php
use Haijin\Validations\CustomValidator;

class Configurable_Address_Validator extends CustomValidator
{
    public function __construct($maxLength = 255)
    {
        parent::__construct();

        $this->maxLength = $maxLength;
    }

    public function evaluate()
    {
        $this->isArray();

        $this->attr('street') ->isDefined() ->isString() ->length( 0, $this->maxLength );

        $this->attr('number') ->isDefined() ->isString() ->length( 0, $this->maxLength );
    }
}

// and use it like this:

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$validator = new Validator();

$validationErrors = $validator->validate( $user, function($user) {

    $user ->isPresent();

    $user->attr('name') ->isDefined() ->isString();

    $user->attr('lastName') ->isDefined() ->isString();

    $user->attr('address') ->isDefined()
            ->validateWith( new Configurable_Address_Validator( 30 ) );

});
```

<a name="c-2-4"></a>
### Custom validations with callables

Perform simple validations using a closure or callable:

```php
use Haijin\Validations\Validator;

$n = 1;

$validator = new Validator();

$validationErrors = $validator->validate( $n, function($n) {

    $n ->isDefined() ->isInt();

    $n->validateWith( function($validator) {

        $validator->setValidationName( 'isOdd' );

        if( $this->getValue() % 2 == 0 ){
            $validator->addError();            
        }         

    });

});
```

<a name="c-2-5"></a>
### Validating arrays

Validate each item in an array with:

```php
use Haijin\Validations\Validator;

$numbers = [ 1, 3, 5, 7 ];

$validator = new Validator();

$validationErrors = $validator->validate( $numbers, function($numbers) {

    $numbers ->isDefined() ->isArray();

    $numbers->each( function($n) {

        $n ->isDefined() ->isInt() ->is( '>', 0 );

    });

});
```

### Writting complex validations
 
Some complex validations involve accessing multiple attributes, invoking other validations and performing calculations. In those cases write a custom Validator subclass and use its available protocol.

```php
use Haijin\Validations\CustomValidator;

class A_CustomValidator extends CustomValidator
{
    public function evaluate()
    {
        /// Available functions during validations


        // Define the validation name
        $this->setValidationName( 'complex-validation' );

        // Get the validation name
        $this->getValidationName();

        // Define the validation name
        $this->setValidationParameters( [ 1, 2, 3 ] );

        // Get the validation parameters
        $this->getValidationParameters();

        // Get the validated value
        $this->getValue();

        // Get the value of some other attribute
        $this->getValueAt( 'address.street' );

        // Get the value of some other attribute that is included in an array
        $this->getValueAt( 'items.[3].description' );

        // Get the attribute path
        $this->getAttributePath();

        // Override the attribute path
        $this->setAttributePath( 'address.street' );

        // Get the validation errors previously collected
        $this->getErrors();

        // Add a validation error using the current validationName and default values
        $this->addError();

        // Add a validation error overriding some or all of the default values. Note that is has
        // only para parameters, an associative array, like in the js way.
        $this->addError([
            'value' => 'overriden validated value',
            'attributePath' => 'overriden.attribute',
            'validationName' => 'overriden-validation-name',
            'validationParameters' => [ 'overriden', 'validation', 'parameters' ] 
        ]);

        // Add errors collected from another validation
        $this->addAllErrors( $errors );

        // Halt validations and do not validate this attribute children
        $this->halt();

        // Validate a child attribute
        $this->attr('address') ->isPresent();

        // Validate a child attribute with a custom block
        $this->attr('address') ->isPresent() ->eval( function($address) {
            $address ->isObject();
        });

        // This also works
        $this->attr('address', function($address) {
            $address ->isPresent() ->isObject();
        });
    }
}
```

<a name="c-2-6"></a>
### Halting validations

Validations may halt or not subsequent validations on the attribute branch.

To avoid further validations on an attribute branch call `halt()`.

When halting a validation the validation on its children are not run, but the do continue on its siblings.

Here's an example:

```php
use Haijin\Validations\Validator;

$user = [
    'name' => null,
    'lastName' => 'Simpson',
    'address' => null
];

$validator = new Validator();

$validationErrors = $validator->validate( $user, function($user) {
    $user ->isDefined();

    $user->attr( 'name', function($name) {
        // The isDefined validation fails because name is null and halts. isString is not called.
        $name ->isDefined() ->isString();
    });

    // These validations are still run
    $user->attr( 'lastName', function($lastName) {
        $lastName ->isDefined() ->isString();
    });

    $user->attr( 'address', function($address) {
        // The isDefined validation fails because address is null and halts
        $address->isDefined();

        // These child validations are not run
        $address->attr( 'street' ) ->isDefined();
        $address->attr( 'number' ) ->isDefined();
    });
});
```

<a name="c-2-7"></a>
### Custom validator example

This CustomValidator validates a purchase object:

```php
use Haijin\Validations\CustomValidator;

class Purchase_Validator extends CustomValidator
{
    /**
     * Validates a purchase object.
     */
    public function evaluate()
    {
        $this->attr( 'date' ) ->isDefined();

        $this->attr( 'items' ) ->isDefined() ->isArray() ->each( function($eachItem){
            $eachItem ->isDefined();

            $eachItem->attr( 'description' ) ->isDefined() ->isString();
            $eachItem->attr( 'price' ) ->isDefined() ->isNumber() ->is( '>=', 0 );
        }) ;

        $this->attr( 'total' ) ->isDefined() ->isNumber() ->is( '>=', 0 );

        // Extracted to a method on its own because of its complexity.
        // Only run this validation if there are no previous errors in the purchase.
        if( count( $this->getErrors() ) == 0 )
            $this->validateTotalSum();
    }

    /**
     * Validates that the sum of all item prices equals the purchase total.
     */
    protected function validateTotalSum()
    {
        $this->setValidationName( 'total-sum' );

        $total = $this->getValueAt( 'total' );

        $sum = 0;
        foreach( $this->getValueAt( 'items' ) as $item ) {
            $sum += $item[ 'price' ];
        }

        if( $total != $sum ) {
            $this->addError();
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

$validationErrors = $validator->validate( $purchase, function($purchase) {
    $purchase ->isDefined() ->isObject() ->validateWith( 'Purchase_Validator' );
});
```

<a name="c-3"></a>
## Built-in validation constraints

[Built-in constraints and converters](./documentation/built-in-validation-constraints.md)

<a name="c-4"></a>
## Converters

Converters are regular `Validation` objects that besides validating a value they override it for the next validations run.

```php
$validator = new Validator();

$validationErrors = $validator->validate( '1', function($n) {
    $n ->isDefined() ->isString() ->asInt() ->is( '>', 0 );
});
```

Write a custom converter with:

```php
use Haijin\Validations\CustomValidator;

class Increment_Converter extends CustomValidator
{
    public function evaluate()
    {
        $value = $this->getValue();

        $this->setValue( $value + 1 );
    }
}
```

<a name="c-5"></a>
## Adding and overriding built-in validations and converters

Override and add built in validations defining methods in a CustomValidator subclass and using that subclass instead of CustomValidator:
 
```php
use Haijin\Validations\Validator;

class ExtendedValidator extends Validator
{
    public function isAddress()
    {
        $this->attr( 'street', function($street) {
            $street ->isDefined() ->isString();
        });

        $this->attr( 'number', function($number) {
            $number ->isDefined() ->isString();
        });
    }
}

$validator = new ExtendedValidator();

$validationErrors = $validator->validate( $user, function($user) {
    $user ->isDefined();

    $user->attr( 'name' ) ->isDefined() ->isString();

    $user->attr( 'lastName' ) ->isDefined() ->isString();

    $user->attr( 'address' ) ->isAddress();
});
```

<a name="c-6"></a>
## Integrating the validations in applications

Example that validates an object before storing it in a database.

Integrate the validations like this:

```php
use Haijin\Validations\Validator;

abstract class PersistentCollection
{
    public function save($object)
    {
        $validationErrors = $this->validateBeforeSaving( $object );

        if( count( $validationErrors ) > 0 ) {
            return $validationErrors;
        }

        $this->doSave( $object );
    }

    public function doSave($object)
    {
        // actual saving here ...
    }

    protected function validateBeforeSaving($object)
    {
        $validator = new Validator;

        return $validator->validate( $object, function($object) {
            $this->validate( $object );
        });
    }

    abstract protected function validate($validator);
}

class UserPersistentCollection extends PersistentCollection
{
    protected function validate($user)
    {
        $user->attr( 'name', function($name){
            $name ->isDefined() ->isString() ->length( 1, 255 );
        });

        $user->attr( 'lastName', function($lastName) {
            $lastName ->isDefined() ->isString() ->length( 1, 255 );
        });

        // etc ...
    }
}

// And then use it like this:

$user = [
    'name' => 123,
    'lastName' => null,
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$persistentCollection = new UserPersistentCollection();

$validationErrors = $persistentCollection->save( $user );

varDump( $validationErrors );
```

<a name="c-7"></a>
## Validation messages

The validation message is not part of the ValidationError object. Validation messages are handled in a definition of their own.

This allows to conveniently define and override the messages to the end users in any part of the application.

[Code example of defining validation messages](./documentation/validation-message-example.php).

<a name="c-7-1"></a>
### Create a new ValidationMessagesDictionary

Create a new dictionary

```php
use Haijin\Validations\Validator;

$validationMessagesDictionary = new ValidationMessagesDictionary();
```

and optionally add the default messages to it

```php
use Haijin\Validations\Validator;

$validationMessagesDictionary = new ValidationMessagesDictionary();

$validationMessagesDictionary->withDefaultMessages();
```

<a name="c-7-2"></a>
### Define a default validation message

```php
$validationMessagesDictionary->define( function($messages) {

    $messages->default( function($validationError) {
        return "The attribute '{$validationError->getAttributeName()}' is not valid.";
    });

});
```

Get the validations messages for each validation error:

```php
foreach( $validationErrors as $error ) {

    $message = $validationMessagesDictionary->messageFor( $error );

    print( $message );
    print "\n";

}
```

<a name="c-7-3"></a>
### Define more specific messages

```php
$validationMessagesDictionary->define( function($messages) {

    $messages->atValidation( 'notBlank', function($validationError) {
        return "The attribute '{$validationError->getAttributeName()}' must contain visible characters.";
    });

    $messages->atValidation( 'length', function($validationError) {
        $params = $validationError->getValidationParameters();

        return "The attribute '{$validationError->getAttributeName()}' length must be in the range ({$params[0]}, {$params[1]})";
    });
});
```

Get the validations messages

```php
foreach( $validationErrors as $error ) {
    print( $errorsDictionary->messageFor( $error ) );
    print "\n";
}
```

<a name="c-8"></a>
## Running the tests

```
composer specs
```

Or if you want to run the tests using a Docker image with PHP 7.2:

```
sudo docker run -ti -v $(pwd):/home/php-validations --rm --name php-validations haijin/php-dev:7.2 bash
cd /home/php-validations/
composer install
composer specs
```
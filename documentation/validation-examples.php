<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Haijin\Debugger;
use Haijin\Validations\Validator;
use Haijin\Validations\CustomValidator;

/// Using a Validator object

/*
 *   Create a Validator instance and call validate on the object:
 */

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$validator = new Validator();

$validator->validate($user, function ($user) {

    $user->isPresent();

    $user->attr('name')->isDefined()->isString();

    $user->attr('lastName')->isDefined()->isString();

    $user->attr('address')->isDefined()->isArray()->eval(function ($address) {

        $address->attr('street')->isDefined()->isString();

        $address->attr('number')->isDefined()->isString();

    });

});

$validationErrors = $validator->getErrors();

/*
 * Reuse frequent validations in a Validator subclass:
 */

class AddressValidator extends Validator
{
    public function evaluate()
    {
        $this->isArray();

        $this->attr('street', function ($street) {
            $street->isDefined()->isString();
        });

        $this->attr('number', function ($number) {
            $number->isDefined()->isString();
        });
    }
}

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$validator = new Validator();

$validationErrors = $validator->validate($user, function ($user) {

    $user->isPresent();

    $user->attr('name')->isDefined()->isString();

    $user->attr('lastName')->isDefined()->isString();

    $user->attr('address')->isDefined()->validateWith(AddressValidator::class);

});


/**
 * Pass a CustomValidator subclass instance instead of its name to parametrized it:
 */
class ConfigurableAddressValidator extends CustomValidator
{
    public function __construct($maxLength = 255)
    {
        parent::__construct();

        $this->maxLength = $maxLength;
    }

    public function evaluate()
    {
        $this->isArray();

        $this->attr('street')->isDefined()->isString()
            ->length(0, $this->maxLength);

        $this->attr('number')->isDefined()->isString()
            ->length(0, $this->maxLength);
    }
}

$user = [
    'name' => 'Lisa',
    'lastName' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

$validator = new Validator();

$validationErrors = $validator->validate($user, function ($user) {

    $user->isPresent();

    $user->attr('name')->isDefined()->isString();

    $user->attr('lastName')->isDefined()->isString();

    $user->attr('address')->isDefined()
        ->validateWith(new ConfigurableAddressValidator(30));

});

/**
 * Perform simple validations using a callable.
 */

$n = 1;

$validator = new Validator();

$validationErrors = $validator->validate($n, function ($n) {

    $n->isDefined()->isInt();

    $n->validateWith(function ($validator) {

        $validator->setValidationName('isOdd');

        if ($validator->getValue() % 2 == 0) {
            $validator->addError();
        }

    });

});

/// Validating arrays

/**
 * Validate items in arrays with
 */

$numbers = [1, 3, 5, 7];

$validator = new Validator();

$validationErrors = $validator->validate($numbers, function ($numbers) {

    $numbers->isDefined()->isArray();

    $numbers->each(function ($n) {

        $n->isDefined()->isInt()->is('>', 0);

    });

});


/// Writting custom validations.

/**
 * Some validations involve accessing multiple attributes, invoking other validations and performing
 * complex calculations. In those cases write a CustomValidator subclass and use its available protocol
 * to fullfill the validation needs.
 */
class AValidator extends CustomValidator
{
    public function evaluate()
    {
        // Define the validation name
        $this->setValidationName('complex-validation');

        // Get the validation name
        $this->getValidationName();

        // Define the validation name
        $this->setValidationParameters([1, 2, 3]);

        // Get the validation parameters
        $this->getValidationParameters();

        // Get the validated value
        $this->getValue();

        // Get the value of some other attribute
        $this->getValueAt('address.street');

        // Get the value of some other attribute that is included in an array
        $this->getValueAt('items.[3].description');

        // Get the attribute path
        $this->getAttributePath();

        // Override the attribute path
        $this->setAttributePath('address.street');

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
            'validationParameters' => ['overriden', 'validation', 'parameters']
        ]);

        // Add errors collected from another validation
        $this->addAllErrors($errors);

        // Halt validations and do not validate this attribute children
        $this->halt();

        // Validate a child attribute
        $this->attr('address')->isPresent();

        // Validate a child attribute with a custom block
        $this->attr('address')->isPresent()->eval(function ($address) {
            $address->isObject();
        });

        // This also works
        $this->attr('address', function ($address) {
            $address->isPresent()->isObject();
        });
    }
}

/// Halting validations

/*
 * Validations may halt or not subsequent validations.
 * To avoid further validations on an attribute branch call halt().
 * When halting a validation the validation on their children are not run, but the do continue
 * on their siblings.
 * Here's an example:
 */

$user = [
    'name' => null,
    'lastName' => 'Simpson',
    'address' => null
];

$validator = new Validator();

$validationErrors = $validator->validate($user, function ($user) {

    $user->isDefined();

    $user->attr('name', function ($name) {
        // The isDefined validation fails because name is null and halts. isString is not called.
        $name->isDefined()->isString();
    });

    // These validations are still run
    $user->attr('lastName', function ($lastName) {
        $lastName->isDefined()->isString();
    });

    $user->attr('address', function ($address) {
        // The isDefined validation fails because address is null and halts
        $address->isDefined();

        // These child validations are not run
        $address->attr('street')->isDefined();
        $address->attr('number')->isDefined();

    });

});

/// Custom validator example

/**
 * Validates a purchase object.
 */
class PurchaseValidator extends CustomValidator
{
    /**
     * Validates a purchase object.
     */
    public function evaluate()
    {
        $this->attr('date')->isDefined();

        $this->attr('items')->isDefined()->isArray()->each(function ($eachItem) {
            $eachItem->isDefined();

            $eachItem->attr('description')->isDefined()->isString();
            $eachItem->attr('price')->isDefined()->isNumber()->is('>=', 0);
        });

        $this->attr('total')->isDefined()->isNumber()->is('>=', 0);

        // Extracted to a method on its own because of its complexity.
        // Only run this validation if there are no previous errors in the purchase.
        if (count($this->getErrors()) == 0)
            $this->validateTotalSum();
    }

    /**
     * Validates that the sum of all item prices equals the purchase total.
     */
    protected function validateTotalSum()
    {
        $this->setValidationName('total-sum');

        $total = $this->getValueAt('total');

        $sum = 0;
        foreach ($this->getValueAt('items') as $item) {
            $sum += $item['price'];
        }

        if ($total != $sum) {
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

$validationErrors = $validator->validate($purchase, function ($purchase) {
    $purchase->isDefined()->isObject()->validateWith('PurchaseValidator');
});

/// Converters

/*
 * Convert a value before validating it with:
 */

$validator = new Validator();

$validationErrors = $validator->validate('1', function ($n) {
    $n->isDefined()->isString()->asInt()->is('>', 0);
});

/*
 * Write a custom converter with:
 */

class Increment_Converter extends CustomValidator
{
    public function evaluate()
    {
        $value = $this->getValue();

        $this->setValue($value + 1);
    }
}


/// Adding and overriding built-in validations

/**
 * Override and add built in validations defining methods in a Validator subclass and
 * using that subclass instead of Validator:
 */
class ExtendedValidator extends Validator
{
    public function isAddress()
    {
        $this->attr('street', function ($street) {
            $street->isDefined()->isString();
        });

        $this->attr('number', function ($number) {
            $number->isDefined()->isString();
        });
    }
}

$validator = new ExtendedValidator();

$validationErrors = $validator->validate($user, function ($user) {

    $user->isDefined();

    $user->attr('name')->isDefined()->isString();

    $user->attr('lastName')->isDefined()->isString();

    $user->attr('address')->isAddress();

});

/// Integrating the validations in applications

/**
 * Example:
 *      Validate an object before storing it in a database:
 */
abstract class PersistentCollection
{
    public function save($object)
    {
        $validationErrors = $this->validateBeforeSaving($object);

        if (count($validationErrors) > 0)
            return $validationErrors;

        $this->doSave($object);
    }

    public function doSave($object)
    {
        // actual saving here ...
    }

    protected function validateBeforeSaving($object)
    {
        $validator = new Haijin\Validations\Validator;

        return $validator->validate($object, function ($object) {
            $this->validate($object);
        });
    }

    abstract protected function validate($validator);
}

class UserPersistentCollection extends PersistentCollection
{
    protected function validate($user)
    {
        $user->attr('name', function ($name) {
            $name->isDefined()->isString()->length(1, 255);
        });

        $user->attr('lastName', function ($lastName) {
            $lastName->isDefined()->isString()->length(1, 255);
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
$validationErrors = $persistentCollection->save($user);

Debugger::inspect($validationErrors);
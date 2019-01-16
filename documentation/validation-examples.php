<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Haijin\Validations\Validator;

/// Using a Validator object

/*
 *   Create a Validator instance and call validate on the object:
 */

$user = [
    'name' => 'Lisa',
    'last_name' => 'Simpson',
    'address' => [
        'street' => 'Evergreen',
        'number' => null
    ]
];

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

// or also

$validator->validate( $user, function($user) {
    $user ->is_present();

    $user['name'] ->is_defined() ->is_string();

    $user['last_name'] ->is_defined() ->is_string();

    $user['address'] ->is_defined() ->is_array() ->eval( function($address) {

        $address['street'] ->is_defined() ->is_string();

        $address['number'] ->is_defined() ->is_string();
    });
});

/// Custom validations

/*
 * Reuse frequent validations in a Validator subclass:
 */

class Address_Validator extends Validator
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

    $user->attr('address') ->is_defined() ->validate_with( 'Address_Validator' );
});


/**
 * Pass a Validator subclass instance instead of its name to parametrized it:
 */

class Configurable_Address_Validator extends Validator
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

    $user->attr('address') ->is_defined() ->validate_with( new Configurable_Address_Validator( 30 ) );
});

/**
 * Perform simple validations using a closure.
 */

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

/// Validating arrays

/**
 * Validate items in arrays with
 */

$numbers = [ 1, 3, 5, 7 ];

$validator = new Validator();

$validation_errors = $validator->validate( $numbers, function($numbers) {
    $numbers ->is_defined() ->is_array();

    $numbers->each( function($n) {

        $n ->is_defined() ->is_int() ->is( '>', 0 );

    });
});


 /// Writting custom validations.
 
 /**
  * Some validations involve accessing multiple attributes, invoking other validations and performing 
  * complex calculations. In those cases write a custom Validator subclass and use its available protocol
  * to fullfill the validation needs.
  */

 class Custom_Validator extends Validator
 {
    public function evaluate()
    {
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

/// Custom validator example

/**
 * Validates a purchase object.
 */
class Purchase_Validator extends Validator
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
    $purchase ->is_defined() ->is_object() ->validate_with( 'Purchase_Validator' );
});

/// Converters

/*
 * Convert a value before validating it with:
 */

$validator = new Validator();

$validation_errors = $validator->validate( '1', function($n) {
    $n ->is_defined() ->is_string() ->as_int() ->is( '>', 0 );
});

/*
 * Write a custom converter with:
 */

class Increment_Converter extends Validator
{
    public function evaluate()
    {
        $value = $this->get_value();

        $this->set_value( $value + 1 );
    }
}


/// Adding and overriding built-in validations

/**
 * Override and add built in validations defining methods in a Validator subclass and using that subclass
 * instead of Validator:
 */

class Extended_Validator extends Validator
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

$validator = new Extended_Validator();

$validation_errors = $validator->validate( $user, function($user) {
    $user ->is_defined();

    $user->attr( 'name' ) ->is_defined() ->is_string();

    $user->attr( 'last_name' ) ->is_defined() ->is_string();

    $user->attr( 'address' ) ->is_address();
});

/// Integrating the validations in applications

/**
 * Example: 
 *      Validate an object before storing it in a database:
 */

abstract class Persistent_Collection
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

class User_Persisten_Collection extends Persistent_Collection
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

$persistent_collection = new User_Persisten_Collection();
$validation_errors = $persistent_collection->save( $user );

var_dump( $validation_errors );
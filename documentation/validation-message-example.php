<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Haijin\Validations\Validator;
use Haijin\Validations\Messages\Validation_Messages_Dictionary;

// Collect some validations

$object = new stdclass();
$object->name = '  ';

$validation_errors = ( new Validator() )->validate( $object, function($user) {

    $user->attr( 'name' ) ->is_present() ->is_string() ->not_blank() ->length( 8, 30 );

});


/// Create a new Validation_Messages_Dictionary

// Use a dictionary with default messages

$errors_dictionary = new Validation_Messages_Dictionary();

$errors_dictionary->with_default_messages();

// or create a new one

$errors_dictionary = new Validation_Messages_Dictionary();

/// Define a default validation message

$errors_dictionary->define( function($messages) {

    $messages->default( function($validation_error) {
        return "The attribute '{$validation_error->get_attribute_name()}' is not valid.";
    });

});

// Get the validations messages

foreach( $validation_errors as $error ) {

    $message = $errors_dictionary->message_for( $error );

    print( $message );
    print "\n";

}

/// Define more specific messages

$errors_dictionary->define( function($messages) {

    $messages->at_validation( 'not_blank', function($validation_error) {
        return "The attribute '{$validation_error->get_attribute_name()}' must contain visible characters.";
    });

    $messages->at_validation( 'length', function($validation_error) {
        $params = $validation_error->get_validation_parameters();

        return "The attribute '{$validation_error->get_attribute_name()}' length must be in the range ({$params[0]}, {$params[1]})";
    });
});

// Get the validations messages

foreach( $validation_errors as $error ) {

    $message = $errors_dictionary->message_for( $error );

    print( $message );
    print "\n";

}

<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Haijin\Validations\Messages\ValidationMessagesDictionary;
use Haijin\Validations\Validator;

// Collect some validations

$object = new stdclass();
$object->name = '  ';

$validationErrors = (new Validator())->validate($object, function ($user) {

    $user->attr('name')->isPresent()->isString()->notBlank()->length(8, 30);

});


/// Create a new ValidationMessagesDictionary

// Use a dictionary with default messages

$errorsDictionary = new ValidationMessagesDictionary();

$errorsDictionary->withDefaultMessages();

// or create a new one

$errorsDictionary = new ValidationMessagesDictionary();

/// Define a default validation message

$errorsDictionary->define(function ($messages) {

    $messages->default(function ($validationError) {
        return "The attribute '{$validationError->getAttributeName()}' is not valid.";
    });

});

// Get the validations messages

foreach ($validationErrors as $error) {

    $message = $errorsDictionary->messageFor($error);

    print($message);
    print "\n";

}

/// Define more specific messages

$errorsDictionary->define(function ($messages) {

    $messages->atValidation('notBlank', function ($validationError) {
        return "The attribute '{$validationError->getAttributeName()}' must contain visible characters.";
    });

    $messages->atValidation('length', function ($validationError) {
        $params = $validationError->getValidationParameters();

        return "The attribute '{$validationError->getAttributeName()}' length must be in the range ({$params[0]}, {$params[1]})";
    });
});

// Get the validations messages

foreach ($validationErrors as $error) {

    $message = $errorsDictionary->messageFor($error);

    print($message);
    print "\n";

}

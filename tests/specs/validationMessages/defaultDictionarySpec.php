<?php

use Haijin\AttributePath;
use Haijin\Validations\Messages\ValidationMessagesDictionary;
use Haijin\Validations\ValidationError;

$spec->describe("When using the default ValidationMessagesDictionary", function () {

    $this->let("dictionary", function () {

        return (new ValidationMessagesDictionary())->withDefaultMessages();

    });

    $this->it("the formatted message for the isPresent validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isPresent',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be present.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the notPresent validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'notPresent',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must not be present.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isEmpty validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isEmpty',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be empty.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the notEmpty validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'notEmpty',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must not be empty.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isDefined validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isDefined',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be defined.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the notDefined validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'notDefined',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must not be defined.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isBlank validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isBlank',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be blank.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the notBlank validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'notBlank',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must not be blank.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the matches validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'matches',
            ['/expression/']
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must match '/expression/'.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isString validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isString',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be a string.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isEmail validation is defined", function () {

        $validationError = new ValidationError(
            'lisaSimpson@evergreen',
            new AttributePath('address.street'),
            'isEmail',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' is not a valid email.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isInt validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isInt',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be an int.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isFloat validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isFloat',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be a float.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isNumber validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isNumber',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be a number.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isBoolean validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isBoolean',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be a boolean.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isArray validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isArray',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be an array.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isObject validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isObject',
            []
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be an object.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the == validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            '==',
            [2]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must equal 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the != validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            '!=',
            [2]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must not equal 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the < validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            '<',
            [2]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be < 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the <= validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            '<=',
            [2]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be <= 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the > validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            '>',
            [2]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be > 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the >= validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            '>=',
            [2]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be >= 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the ~ validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            '~',
            [2, 0.01]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be ~ 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the !~ validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            '!~', [2, 0.01]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be !~ 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the sameValueAt validation is defined", function () {

        $validationError = new ValidationError(
            '123',
            new AttributePath('passwordConfirmation'),
            'sameValueAt',
            ['password']
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'passwordConfirmation' does not match the attribute 'password'.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the has validation is defined", function () {

        $validationError = new ValidationError(
            [],
            new AttributePath('address.street'),
            'has',
            [2]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must have the element 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the hasNot validation", function () {

        $validationError = new ValidationError(
            [2],
            new AttributePath('address.street'),
            'hasNot',
            [2]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must not have the element 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the hasAll validation is defined", function () {

        $validationError = new ValidationError(
            [2],
            new AttributePath('address.street'),
            'hasAll',
            [[2, 3]]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must have all the elements [2, 3].";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the hasAny validation is defined", function () {

        $validationError = new ValidationError(
            [2],
            new AttributePath('address.street'),
            'hasAny',
            [[2, 3]]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must have any of the elements [2, 3].";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the hasNone validation is defined", function () {

        $validationError = new ValidationError(
            [2],
            new AttributePath('address.street'),
            'hasNone',
            [[2, 3]]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must have none of the elements [2, 3].";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the isIn validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'isIn',
            [[2, 3]]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be one of [2, 3].";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the notIn validation is defined", function () {

        $validationError = new ValidationError(
            1,
            new AttributePath('address.street'),
            'notIn',
            [[2, 3]]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must be none of [2, 3].";

        $this->expect($message)->to()->equal($expectedMessage);

    });

    $this->it("the formatted message for the length validation is defined", function () {

        $validationError = new ValidationError(
            [2],
            new AttributePath('address.street'),
            'length',
            [2, 3]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must have a length between 2 and 3.";

        $this->expect($message)->to()->equal($expectedMessage);


        $validationError = new ValidationError(
            [2],
            new AttributePath('address.street'),
            'length',
            [null, 3]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must have a maximum length of 3.";

        $this->expect($message)->to()->equal($expectedMessage);


        $validationError = new ValidationError(
            [2],
            new AttributePath('address.street'),
            'length',
            [2, null]
        );

        $message = $this->dictionary->messageFor($validationError);

        $expectedMessage = "The attribute 'address.street' must have a minimum length of 2.";

        $this->expect($message)->to()->equal($expectedMessage);

    });

});
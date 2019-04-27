<?php

use Haijin\AttributePath;
use Haijin\Validations\Messages\ValidationMessageNotFoundException;
use Haijin\Validations\Messages\ValidationMessagesDictionary;
use Haijin\Validations\ValidationError;

$spec->describe("A ValidationMessagesDictionary", function () {

    $this->let("dictionary", function () {

        return new ValidationMessagesDictionary();

    });

    $this->it("RaisesAnErrorIfNoMessageIsFound", function () {

        $validationError = new ValidationError([2], new AttributePath('address.street'), 'length', [2, 3]);

        $this->expect(function () use ($validationError) {

            $this->dictionary->messageFor($validationError);

        })->to()->raise(
            ValidationMessageNotFoundException::class,
            function ($error) use ($validationError) {

                $this->expect($error->getValidationError())->to()->be("===")
                    ->than($validationError);

                $this->expect($error->getMessage())->to()
                    ->equal('No message formatter was found for the ValidationError "length"');

            });

    });

    $this->it("DefaultMessage", function () {

        $this->dictionary->define(function ($dict) {

            $dict->default(function ($validationError) {
                return "Invalid value";
            });

        });

        $validationError = new ValidationError([2], new AttributePath('address.street'), 'length', [2, 3]);

        $message = $this->dictionary->messageFor($validationError);

        $this->expect($message)->to()->equal("Invalid value");

    });

    $this->it("DroppingDefaultMessage", function () {

        $this->dictionary->define(function ($dict) {

            $dict->default(function ($validationError) {
                return "Invalid value";
            });

        });

        $this->dictionary->dropDefault();

        $this->expect(function () {

            $validationError = new ValidationError([2], new AttributePath('address.street'), 'length', [2, 3]);

            $this->dictionary->messageFor($validationError);

        })->to()->raise(
            ValidationMessageNotFoundException::class,
            function ($error) {

                $this->expect($error->getMessage())->to()
                    ->equal('No message formatter was found for the ValidationError "length"');
            });

    });

    $this->it("HasDefaultMessage", function () {

        $this->expect($this->dictionary->hasDefault())->to()->be()->false();

        $this->dictionary->default(function ($validationError) {
            return "Invalid value";
        });

        $this->expect($this->dictionary->hasDefault())->to()->be()->true();

        $this->dictionary->dropDefault();

        $this->expect($this->dictionary->hasDefault())->to()->be()->false();

    });

    $this->it("ValidationMessage", function () {

        $this->dictionary->define(function ($dict) {

            $dict->atValidation("length", function ($validationError) {
                return "Invalid length";
            });

        });

        $validationError = new ValidationError([2], new AttributePath('address.street'), 'length', [2, 3]);

        $message = $this->dictionary->messageFor($validationError);

        $this->expect($message)->to()->equal("Invalid length");

    });

    $this->it("DroppingValidationMessage", function () {

        $this->dictionary->define(function ($dict) {

            $dict->atValidation("length", function ($validationError) {
                return "Invalid length";
            });

        });

        $this->dictionary->define(function ($dict) {
            $dict->dropValidation("length");
        });

        $this->expect(function () {

            $validationError = new ValidationError([2], new AttributePath('address.street'), 'length', [2, 3]);

            $this->dictionary->messageFor($validationError);

        })->to()->raise(
            ValidationMessageNotFoundException::class,
            function ($error) {

                $this->expect($error->getMessage())->to()
                    ->equal('No message formatter was found for the ValidationError "length"');

            });

    });

    $this->it("ValidationMessageOverridesDefaultMessage", function () {

        $this->dictionary->define(function ($dict) {

            $dict->atValidation("length", function ($validationError) {
                return "Invalid length";
            });

            $dict->default(function ($validationError) {
                return "Invalid value";
            });

        });

        $validationError = new ValidationError([2], new AttributePath('address.street'), 'length', [2, 3]);

        $message = $this->dictionary->messageFor($validationError);

        $this->expect($message)->to()->equal("Invalid length");

    });

});
<?php

use Haijin\Validations\ValidationErrorException;
use Haijin\Validations\Validator;

$spec->describe("When using the Validator protocol", function () {

    $this->it("accessing the validated value", function () {

        $validator = new Validator(null);
        $validator->setValue(123);

        $this->expect($validator->getValue())->to()->equal(123);

    });

    $this->it("accessing the attribute path", function () {

        $validator = new Validator(123);
        $validator->setAttributePath('address.street');

        $this->expect($validator->getAttributePath()->toString())->to()
            ->equal('address.street');

    });

    $this->it("creating validation errors", function () {

        $validator = new Validator();

        $validationError = $validator->newValidationError([
            'value' => 123,
            'attributePath' => 'address.street',
            'validationName' => 'custom-validation',
            'validationParameters' => [1, 2]
        ]);

        $this->expect($validationError->getAttributeName())
            ->to()->equal('street');

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'custom-validation',
            'getValidationParameters()' => [1, 2]
        ]);

        // Default value

        $validator = new Validator();
        $validator->setValue(123);
        $validator->setAttributePath('address.street');

        $validationError = $validator->newValidationError([
            'attributePath' => 'address.street',
            'validationName' => 'custom-validation',
            'validationParameters' => [1, 2]
        ]);

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'custom-validation',
            'getValidationParameters()' => [1, 2]
        ]);

        // Default attributePath

        $validator = new Validator();
        $validator->setValue(123);
        $validator->setAttributePath('address.street');

        $validationError = $validator->newValidationError([
            'value' => 123,
            'validationName' => 'custom-validation',
            'validationParameters' => [1, 2]
        ]);

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'custom-validation',
            'getValidationParameters()' => [1, 2]
        ]);

        // Default validationName

        $validator = new Validator();
        $validator->setValue(123);
        $validator->setAttributePath('address.street');

        $validationError = $validator->newValidationError([
            'value' => 123,
            'attributePath' => 'address.street',
            'validationParameters' => [1, 2]
        ]);

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => null,
            'getValidationParameters()' => [1, 2]
        ]);

        // Default validationParameters

        $validator = new Validator();
        $validator->setValue(123);
        $validator->setAttributePath('address.street');

        $validationError = $validator->newValidationError([
            'value' => 123,
            'attributePath' => 'address.street',
            'validationName' => 'custom-validation'
        ]);

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'custom-validation',
            'getValidationParameters()' => []
        ]);

    });


    $this->it("adding new validation errors", function () {

        $validator = new Validator();

        $validator->addError([
            'value' => 123,
            'attributePath' => 'address.street',
            'validationName' => 'custom-validation',
            'validationParameters' => [1, 2]
        ]);

        $validationError = $validator->getErrors()[0];

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'custom-validation',
            'getValidationParameters()' => [1, 2]
        ]);

        // Default value

        $validator = new Validator();
        $validator->setValue(123);
        $validator->setAttributePath('address.street');

        $validator->addError([
            'attributePath' => 'address.street',
            'validationName' => 'custom-validation',
            'validationParameters' => [1, 2]
        ]);

        $validationError = $validator->getErrors()[0];

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'custom-validation',
            'getValidationParameters()' => [1, 2]
        ]);

        // Default attributePath

        $validator = new Validator();
        $validator->setValue(123);
        $validator->setAttributePath('address.street');

        $validator->addError([
            'value' => 123,
            'validationName' => 'custom-validation',
            'validationParameters' => [1, 2]
        ]);

        $validationError = $validator->getErrors()[0];

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'custom-validation',
            'getValidationParameters()' => [1, 2]
        ]);

        // Default validationName

        $validator = new Validator();
        $validator->setValue(123);
        $validator->setAttributePath('address.street');

        $validator->addError([
            'value' => 123,
            'attributePath' => 'address.street',
            'validationParameters' => [1, 2]
        ]);

        $validationError = $validator->getErrors()[0];

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => null,
            'getValidationParameters()' => [1, 2]
        ]);

        // Default validationParameters

        $validator = new Validator();
        $validator->setValue(123);
        $validator->setAttributePath('address.street');

        $validator->addError([
            'value' => 123,
            'attributePath' => 'address.street',
            'validationName' => 'custom-validation'
        ]);

        $validationError = $validator->getErrors()[0];

        $this->expect($validationError)->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'custom-validation',
            'getValidationParameters()' => []
        ]);

    });

});
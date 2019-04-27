<?php

use Haijin\Validations\Validator;

$spec->describe("When validating nested attributes in validations of associative arrays", function () {

    $this->def("incrementCalledCounter", function () {

        $this->calledCounter += 1;
    });

    $this->it("nested attributes passes", function () {

        $user = [
            'name' => 'Lisa',
            'lastName' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($user, function ($user) {
            $user->isPresent();

            $user->attr('address', function ($address) {
                $address->isPresent();

                $address->attr('street', function ($street) {
                    $street->isString();
                });
            });
        });

        $this->expect(count($validationErrors))->to()->equal(0);

    });

    $this->it("nested attributes fails", function () {

        $user = [
            'name' => 'Lisa',
            'lastName' => 'Simpson',
            'address' => [
                'street' => 123
            ]
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($user, function ($user) {
            $user->isPresent();

            $user->attr('address', function ($address) {
                $address->isPresent();

                $address->attr('street', function ($street) {
                    $street->isString();
                });
            });
        });

        $this->expect(count($validationErrors))->to()->equal(1);

        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'isString',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("this binding is kept across nested attribute validations", function () {

        $user = [
            'name' => 'Lisa',
            'lastName' => 'Simpson',
            'address' => [
                'street' => 123
            ]
        ];

        $validator = new Validator();

        $this->calledCounter = 0;

        $validationErrors = $validator->validate($user, function ($user) {
            $this->incrementCalledCounter();

            $user->isPresent();

            $user->attr('address', function ($address) {
                $this->incrementCalledCounter();

                $address->isPresent();

                $address->attr('street', function ($street) {
                    $this->incrementCalledCounter();

                    $street->isString();
                });
            });
        });

        $this->expect($this->calledCounter)->to()->equal(3);

    });

    $this->it("nested attribute halts its child attributes", function () {

        $user = [
            'name' => 'Lisa',
            'lastName' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $validator = new Validator();

        $this->calledCounter = 0;

        $validationErrors = $validator->validate($user, function ($user) {

            $user->attr('name', function ($name) {
                $this->incrementCalledCounter();
            });

            $user->attr('lastName', function ($lastName) {
                $this->incrementCalledCounter();
            });

            $user->attr('address', function ($address) {
                $address->halt();

                $address->attr('street', function ($street) {
                    $this->incrementCalledCounter();
                });
            });

        });

        $this->expect($this->calledCounter)->to()->equal(2);

    });

    $this->it("sibling branches are validated even when a branch fails", function () {

        $user = [
            'name' => 'Lisa',
            'lastName' => null,
            'address' => [
                'street' => 123
            ]
        ];

        $validator = new Validator();

        $this->calledCounter = 0;

        $validationErrors = $validator->validate($user, function ($user) {

            $user->attr('name', function ($name) {
                $this->incrementCalledCounter();
                $name->isPresent();
            });

            $user->attr('lastName', function ($lastName) {
                $this->incrementCalledCounter();
                $lastName->isPresent();
            });

            $user->attr('address', function ($address) {
                $address->isPresent();

                $address->attr('street', function ($street) {
                    $this->incrementCalledCounter();

                    $street->isPresent();
                });
            });

        });

        $this->expect($this->calledCounter)->to()->equal(3);

        $this->expect(count($validationErrors))->to()->equal(1);

        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => null,
            'getAttributePath()' => 'lastName',
            'getValidationName()' => 'isPresent',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("nested attributes with no callables provided passes", function () {

        $user = [
            'name' => 'Lisa',
            'lastName' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $validator = new Validator();

        $this->calledCounter = 0;

        $validationErrors = $validator->validate($user, function ($user) {

            $user->attr('name')->isPresent();

            $user->attr('lastName')->isPresent();

            $user->attr('address', function ($address) {
                $address->isPresent();

                $address->attr('street')->isPresent();
            });

        });

        $this->expect(count($validationErrors))->to()->equal(0);

    });

    $this->it("nested attributes with no callables provided fails", function () {

        $user = [
            'name' => 'Lisa',
            'lastName' => 'Simpson',
            'address' => [
                'street' => null
            ]
        ];

        $validator = new Validator();

        $this->calledCounter = 0;

        $validationErrors = $validator->validate($user, function ($user) {

            $user->attr('name')->isPresent();

            $user->attr('lastName')->isPresent();

            $user->attr('address', function ($address) {
                $address->isPresent();

                $address->attr('street')->isPresent();
            });

        });

        $this->expect(count($validationErrors))->to()->equal(1);

        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => null,
            'getAttributePath()' => 'address.street',
            'getValidationName()' => 'isPresent',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("values are kept when nested attributes assigning values passes", function () {

        $user = [
            'name' => 'Lisa',
            'lastName' => 'Simpson',
            'address' => [
                'street' => 'Evergreen',
                'number' => '742'
            ]
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($user, function ($user) {
            $user->isPresent();

            $user->attr('address', function ($address) {
                $address->isPresent();

                $address->attr('street', function ($street) {
                    $street->isString();
                });

                $address->attr('number', function ($number) {
                    $number->isString()->asInt();
                });
            });
        });

        $this->expect($validator->getValue())->to()->be()->like([
            "address" => [
                "number" => function ($value) {
                    $this->expect($value)->to()->be("===")->than(742);
                }
            ]
        ]);

    });

});
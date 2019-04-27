<?php

use Haijin\Validations\Validator;

$spec->describe("When using custom closures to perform validations", function () {

    $this->it("validation closure passes", function () {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 7
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {

            $obj->validateWith(function ($validator) {
                $validator->setValidationName('invalid-total');

                $prices = $validator->getValue();

                if ($prices['price_1'] + $prices['price_2'] == $prices['total'])
                    return;

                $validator->addError();
            });

        });

        $this->expect(count($validationErrors))->to()->equal(0);

    });

    $this->it("validation closure fails", function () {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 6
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->validateWith(function ($validator) {
                $validator->setValidationName('invalid-total');

                $prices = $validator->getValue();

                if ($prices['price_1'] + $prices['price_2'] == $prices['total'])
                    return;

                $validator->addError();
            });
        });

        $this->expect(count($validationErrors))->to()->equal(1);

        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => $object,
            'getAttributePath()' => '',
            'getValidationName()' => 'invalid-total',
            'getValidationParameters()' => []
        ]);

    });

});
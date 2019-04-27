<?php

use Haijin\Validations\CustomValidator;
use Haijin\Validations\Validator;

$spec->describe("When using custom Validadors to perform validations", function () {

    $this->it("Validation instance passes", function () {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 7
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {

            $obj->validateWith(new Custom_Validation());

        });

        $this->expect(count($validationErrors))->to()->equal(0);

    });

    $this->it("Validation instance fails", function () {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 6
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {

            $obj->validateWith(new Custom_Validation());

        });

        $this->expect(count($validationErrors))->to()->equal(1);

        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => $object,
            'getAttributePath()' => '',
            'getValidationName()' => 'invalid-total',
            'getValidationParameters()' => []
        ]);

    });


    $this->it("Validation class passes", function () {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 7
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {

            $obj->validateWith('Custom_Validation');

        });

        $this->expect(count($validationErrors))->to()->equal(0);

    });

    $this->it("Validation class fails", function () {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 6
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {

            $obj->validateWith('Custom_Validation');

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


class Custom_Validation extends CustomValidator
{
    public function evaluate()
    {
        $this->setValidationName('invalid-total');

        $prices = $this->getValue();

        if ($prices['price_1'] + $prices['price_2'] == $prices['total']) {
            return;
        }

        $this->addError();
    }
}
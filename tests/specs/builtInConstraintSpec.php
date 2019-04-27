<?php

use Haijin\Errors\HaijinError;
use Haijin\Validations\Validator;

$spec->describe("When using the built-in validation constraints", function () {

    /// Presence constraints

    $this->it("isPresent passes", function () {

        $object = 'a string';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isPresent();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isPresent fails", function () {

        $object = null;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isPresent();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => null,
            'getAttributePath()' => '',
            'getValidationName()' => 'isPresent',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isPresent halts", function () {

        $object = null;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isPresent();

            throw new HaijinError("isPresent should halt its branch");
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => null,
            'getAttributePath()' => '',
            'getValidationName()' => 'isPresent',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("notPresent passes", function () {

        $object = null;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notPresent();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("notPresent fails", function () {

        $object = 'a string';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notPresent();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 'a string',
            'getAttributePath()' => '',
            'getValidationName()' => 'notPresent',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isOptional fails", function () {

        $object = 'a string';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isOptional()->isInt();
        });


        $this->expect(count($validationErrors))->to()->equal(1);

        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 'a string',
            'getAttributePath()' => '',
            'getValidationName()' => 'isInt',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isOptional halts", function () {

        $object = null;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isOptional();

            throw new HaijinError("isOptional should halt and this should not be executed");
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isOptional sets a defualt value", function () {

        $object = null;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isOptional("default value");
        });

        $this->expect($validator->getValue())->to()->equal("default value");

        $this->expect($validationErrors)->to()->equal([]);
    });

    $this->it("isEmpty passes", function () {

        $object = [];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isEmpty();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isEmpty fails", function () {

        $object = [123];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isEmpty();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [123],
            'getAttributePath()' => '',
            'getValidationName()' => 'isEmpty',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("notEmpty passes", function () {

        $object = ['a string'];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notEmpty();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("notEmpty fails", function () {

        $object = [];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notEmpty();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [],
            'getAttributePath()' => '',
            'getValidationName()' => 'notEmpty',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isDefined passes", function () {

        $object = "a string";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isDefined();
        });

        $this->expect($validationErrors)->to()->equal([]);


        $object = [123];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isDefined();
        });

        $this->expect($validationErrors)->to()->equal([]);


        $object = 123;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isDefined();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isDefined fails", function () {

        $object = null;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isDefined();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => null,
            'getAttributePath()' => '',
            'getValidationName()' => 'isDefined',
            'getValidationParameters()' => []
        ]);


        $object = "";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isDefined();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => "",
            'getAttributePath()' => '',
            'getValidationName()' => 'isDefined',
            'getValidationParameters()' => []
        ]);


        $object = " \t\n";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isDefined();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => " \t\n",
            'getAttributePath()' => '',
            'getValidationName()' => 'isDefined',
            'getValidationParameters()' => []
        ]);


        $object = [];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isDefined();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [],
            'getAttributePath()' => '',
            'getValidationName()' => 'isDefined',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isDefined halts", function () {

        $object = null;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isDefined();

            throw new HaijinError("isDefined should halt its branch");
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => null,
            'getAttributePath()' => '',
            'getValidationName()' => 'isDefined',
            'getValidationParameters()' => []
        ]);

    });

    /// String constraints

    $this->it("isBlank passes", function () {

        $object = "";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isBlank();
        });

        $this->expect($validationErrors)->to()->equal([]);

        $object = " \t\n";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isBlank();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isBlank fails", function () {

        $object = "abc \t\n";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isBlank();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => "abc \t\n",
            'getAttributePath()' => '',
            'getValidationName()' => 'isBlank',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("notBlank passes", function () {

        $object = "abc \t\n";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notBlank();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("notBlank fails", function () {

        $object = "";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notBlank();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => "",
            'getAttributePath()' => '',
            'getValidationName()' => 'notBlank',
            'getValidationParameters()' => []
        ]);


        $object = " \t\n";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notBlank();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => " \t\n",
            'getAttributePath()' => '',
            'getValidationName()' => 'notBlank',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("matches passes", function () {

        $object = '$10.00';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->matches('/[$]\d+\.\d\d/');
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("matches fails", function () {

        $object = '10.00';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->matches('/[$]\d+\.\d\d/');
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => "10.00",
            'getAttributePath()' => '',
            'getValidationName()' => 'matches',
            'getValidationParameters()' => ['/[$]\d+\.\d\d/']
        ]);

    });

    $this->it("isEmail passes", function () {

        $object = 'lisa-simpson@evegreen.com';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isEmail();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isEmail fails", function () {

        $object = 'lisa-simpson@evegreen';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isEmail();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => "lisa-simpson@evegreen",
            'getAttributePath()' => '',
            'getValidationName()' => 'isEmail',
            'getValidationParameters()' => []
        ]);

    });

    /// Types constraints

    $this->it("isString passes", function () {

        $object = 'a string';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isString();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isString fails", function () {

        $object = 123;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isString();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 123,
            'getAttributePath()' => '',
            'getValidationName()' => 'isString',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isInt passes", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isInt();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isInt fails", function () {

        $object = '1';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isInt();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => '1',
            'getAttributePath()' => '',
            'getValidationName()' => 'isInt',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isFloat passes", function () {

        $object = 1.0;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isFloat();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isFloat fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isFloat();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => 'isFloat',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isNumber passes", function () {

        $object = 1.0;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isNumber();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isNumber fails", function () {

        $object = '1.0';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isNumber();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => '1.0',
            'getAttributePath()' => '',
            'getValidationName()' => 'isNumber',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isBoolean passes", function () {

        $object = true;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isBoolean();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isBoolean fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isBoolean();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => 'isBoolean',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isArray passes", function () {

        $object = [];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isArray();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isArray fails", function () {

        $object = '';

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isArray();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => '',
            'getAttributePath()' => '',
            'getValidationName()' => 'isArray',
            'getValidationParameters()' => []
        ]);

    });

    $this->it("isObject passes", function () {

        $object = new stdclass();

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isObject();
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isObject fails", function () {

        $object = [];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isObject();
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [],
            'getAttributePath()' => '',
            'getValidationName()' => 'isObject',
            'getValidationParameters()' => []
        ]);

    });

    /// Comparison

    $this->it("equals passes", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('=', 1);
            $obj->is('==', 1);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("equals fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('=', 2);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => '==',
            'getValidationParameters()' => [2]
        ]);


        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('==', 2);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => '==',
            'getValidationParameters()' => [2]
        ]);

    });


    $this->it("differs passes", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('!=', 2);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("differs fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('!=', 1);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => '!=',
            'getValidationParameters()' => [1]
        ]);

    });

    $this->it("lower passes", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('<', 2);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("lower fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('<', 0);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => '<',
            'getValidationParameters()' => [0]
        ]);

    });

    $this->it("lower or equal passes", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('<=', 1);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("lower or equal fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('<=', 0);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => '<=',
            'getValidationParameters()' => [0]
        ]);

    });

    $this->it("greater passes", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('>', 0);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("greater fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('>', 1);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => '>',
            'getValidationParameters()' => [1]
        ]);

    });

    $this->it("greater or equal passes", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('>=', 1);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("greater or equal fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('>=', 2);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => '>=',
            'getValidationParameters()' => [2]
        ]);

    });

    $this->it("equals with precision passes", function () {

        $object = 10.00;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('~', 9.99, 0.01);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("equals with precision fails", function () {

        $object = 10.00;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('~', 0.98, 0.01);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 10.00,
            'getAttributePath()' => '',
            'getValidationName()' => '~',
            'getValidationParameters()' => [0.98, 0.01]
        ]);

    });

    $this->it("differs with precision passes", function () {

        $object = 10.00;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('!~', 9.98, 0.01);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("differs with precision fails", function () {

        $object = 10.00;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->is('!~', 9.99, 0.01);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 10.00,
            'getAttributePath()' => '',
            'getValidationName()' => '!~',
            'getValidationParameters()' => [9.99, 0.01]
        ]);

    });

    $this->it("raises an error with an unkown comparison operator", function () {

        $this->expect(function () {

            $object = 1;

            $validator = new Validator();

            $validator->validate($object, function ($obj) {
                $obj->is('><', 1);
            });

        })->to()->raise(
            HaijinError::class,
            function ($error) {
                $this->expect($error->getMessage())->to()->equal(
                    "Invalid comparison operator '><' in validation. Valid operatos are [ '==', '!=', '>', '>=', '<', '<=', '~', '!~' ]"
                );
            }
        );

    });

    $this->it("sameValueAt passes", function () {

        $object = [
            "a" => 1,
            "b" => 1
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->sameValueAt('b', 'a');
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("sameValueAt fails", function () {

        $object = [
            "a" => 1,
            "b" => 2
        ];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->sameValueAt('b', 'a');
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 2,
            'getAttributePath()' => 'b',
            'getValidationName()' => 'sameValueAt',
            'getValidationParameters()' => ['a', 1]
        ]);

    });

    /// Inclussion

    $this->it("has passes", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->has(3);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("has fails", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->has(4);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [1, 2, 3],
            'getAttributePath()' => '',
            'getValidationName()' => 'has',
            'getValidationParameters()' => [4]
        ]);

    });

    $this->it("hasNot passes", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->hasNot(4);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("hasNot fails", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->hasNot(3);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [1, 2, 3],
            'getAttributePath()' => '',
            'getValidationName()' => 'hasNot',
            'getValidationParameters()' => [3]
        ]);

    });

    $this->it("hasAll passes", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->hasAll([1, 3]);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("hasAll fails", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->hasAll([1, 4]);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [1, 2, 3],
            'getAttributePath()' => '',
            'getValidationName()' => 'hasAll',
            'getValidationParameters()' => [[1, 4]]
        ]);

    });

    $this->it("hasAny passes", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->hasAny([4, 3]);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("hasAny fails", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->hasAny([4, 5]);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [1, 2, 3],
            'getAttributePath()' => '',
            'getValidationName()' => 'hasAny',
            'getValidationParameters()' => [[4, 5]]
        ]);

    });

    $this->it("hasNone passes", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->hasNone([4, 5]);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("hasNone fails", function () {

        $object = [1, 2, 3];

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->hasNone([4, 1]);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [1, 2, 3],
            'getAttributePath()' => '',
            'getValidationName()' => 'hasNone',
            'getValidationParameters()' => [[4, 1]]
        ]);

    });

    $this->it("isIn passes", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isIn([1, 2]);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("isIn fails", function () {

        $object = 3;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->isIn([1, 2]);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 3,
            'getAttributePath()' => '',
            'getValidationName()' => 'isIn',
            'getValidationParameters()' => [[1, 2]]
        ]);

    });

    $this->it("notIn passes", function () {

        $object = 3;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notIn([1, 2]);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("notIn fails", function () {

        $object = 1;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->notIn([1, 2]);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 1,
            'getAttributePath()' => '',
            'getValidationName()' => 'notIn',
            'getValidationParameters()' => [[1, 2]]
        ]);

    });

    /// Length

    $this->it("length passes", function () {

        $validator = new Validator();

        $validationErrors = $validator->validate('', function ($obj) {
            $obj->length(0, 3);
        });

        $this->expect($validationErrors)->to()->equal([]);
        $object = 'abc';


        $validationErrors = $validator->validate('abc', function ($obj) {
            $obj->length(1, 3);
        });

        $this->expect($validationErrors)->to()->equal([]);

    });

    $this->it("length fails", function () {

        $validator = new Validator();

        $validationErrors = $validator->validate('', function ($obj) {
            $obj->length(1, 3);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => '',
            'getAttributePath()' => '',
            'getValidationName()' => 'length',
            'getValidationParameters()' => [1, 3]
        ]);


        $validator = new Validator();

        $validationErrors = $validator->validate('abcd', function ($obj) {
            $obj->length(1, 3);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => 'abcd',
            'getAttributePath()' => '',
            'getValidationName()' => 'length',
            'getValidationParameters()' => [1, 3]
        ]);


        $validator = new Validator();

        $validationErrors = $validator->validate([], function ($obj) {
            $obj->length(1, 3);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [],
            'getAttributePath()' => '',
            'getValidationName()' => 'length',
            'getValidationParameters()' => [1, 3]
        ]);


        $validator = new Validator();

        $validationErrors = $validator->validate([1, 2, 3, 4], function ($obj) {
            $obj->length(1, 3);
        });

        $this->expect(count($validationErrors))->to()->equal(1);
        $this->expect($validationErrors[0])->to()->be()->exactlyLike([
            'getValue()' => [1, 2, 3, 4],
            'getAttributePath()' => '',
            'getValidationName()' => 'length',
            'getValidationParameters()' => [1, 3]
        ]);

    });

});
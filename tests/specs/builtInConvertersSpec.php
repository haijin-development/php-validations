<?php

use Haijin\Validations\Validator;

$spec->describe("When using the built-in validation converters", function () {

    $this->it("asString passes", function () {

        $object = 123;

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->asString();
        });

        $this->expect($validator->getValue())->to()->be("===")->than("123");

    });

    $this->it("asInt passes", function () {

        $object = "123";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->asInt();
        });

        $this->expect($validator->getValue())->to()->be("===")->than(123);

    });

    $this->it("asFloat passes", function () {

        $object = "1.00";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->asFloat();
        });

        $this->expect($validator->getValue())->to()->be("===")->than(1.00);

    });

    $this->it("asBoolean passes", function () {

        $object = "true";

        $validator = new Validator();

        $validationErrors = $validator->validate($object, function ($obj) {
            $obj->asBoolean();
        });

        $this->expect($validator->getValue())->to()->be("===")->than(true);

    });

});
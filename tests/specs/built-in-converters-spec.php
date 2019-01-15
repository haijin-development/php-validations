<?php

use Haijin\Validations\Validator;

$spec->describe( "When using the built-in validation converters", function() {

    $this->it( "as_string passes", function() {

        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->as_string();
        });

        $this->expect( $validator->get_value() ) ->to() ->be( "===" ) ->than( "123" );

    });

    $this->it( "as_int passes", function() {

        $object = "123";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->as_int();
        });

        $this->expect( $validator->get_value() ) ->to() ->be( "===" ) ->than( 123 );

    });

    $this->it( "as_float passes", function() {

        $object = "1.00";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->as_float();
        });

        $this->expect( $validator->get_value() ) ->to() ->be( "===" ) ->than( 1.00 );

    });

    $this->it( "as_boolean passes", function() {

        $object = "true";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->as_boolean();
        });

        $this->expect( $validator->get_value() ) ->to() ->be( "===" ) ->than( true );

    });

});
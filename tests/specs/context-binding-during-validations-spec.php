<?php

use Haijin\Validations\Validator;

$spec->describe( "The binding to this pseudo-variable when using the function validate validation_closure, [this] )", function() {

    $this->def( "validate_method_in_calling_class_context", function($obj) {

        $obj->is_string();

    });

    $this->it( "binds to the validation object by default", function() {

        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_string();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_string',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "binds to the current class", function() {

        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $this->validate_method_in_calling_class_context( $obj );

        }, $this);

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_string',
            'get_validation_parameters()' => []
        ]);

    });

});
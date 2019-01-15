<?php

use Haijin\Validations\Validator;

$spec->describe( "When using custom Validadors to perform validations", function() {

    $this->it( "Validation instance passes", function() {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 7
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( new CustomValidation() );

        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 0 );

    });

    $this->it( "Validation instance fails", function() {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 6
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( new CustomValidation() );

        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => $object,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'invalid-total',
            'get_validation_parameters()' => []
        ]);

    });


    $this->it( "Validation class passes", function() {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 7
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( 'CustomValidation' );

        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 0 );

    });

    $this->it( "Validation class fails", function() {

        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 6
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( 'CustomValidation' );

        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => $object,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'invalid-total',
            'get_validation_parameters()' => []
        ]);

    });

});


class CustomValidation extends Validator
{
    public function evaluate()
    {
        $this->set_validation_name( 'invalid-total' );

        $prices = $this->get_value();

        if( $prices['price_1'] + $prices['price_2'] == $prices['total'] )
            return;

        $this->add_error();
    }
}
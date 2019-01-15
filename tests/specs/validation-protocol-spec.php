<?php

use Haijin\Validations\Validator;
use Haijin\Validations\ValidationErrorException;

$spec->describe( "When using the Validator protocol", function() {

    $this->it( "accessing the validated value", function() {

        $validator = new Validator( null );
        $validator->set_value( 123 );

        $this->expect( $validator->get_value() ) ->to() ->equal( 123 );

    });

    $this->it( "accessing the attribute path", function() {

        $validator = new Validator( 123 );
        $validator->set_attribute_path( 'address.street' );

        $this->expect( $validator->get_attribute_path()->to_string() ) ->to()
            ->equal( 'address.street' );

    });

    $this->it( "creating validation errors", function() {

        $validator = new Validator();

        $validation_error = $validator->new_validation_error([
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
        ]);

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'custom-validation',
            'get_validation_parameters()' => [1, 2]
        ]);

        // Default value

        $validator = new Validator();
        $validator->set_value( 123 );
        $validator->set_attribute_path( 'address.street' );

        $validation_error = $validator->new_validation_error([
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
        ]);

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'custom-validation',
            'get_validation_parameters()' => [1, 2]
        ]);

        // Default attribute_path

        $validator = new Validator();
        $validator->set_value( 123 );
        $validator->set_attribute_path( 'address.street' );

        $validation_error = $validator->new_validation_error([
            'value' => 123,
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
        ]);

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'custom-validation',
            'get_validation_parameters()' => [1, 2]
        ]);

        // Default validation_name

        $validator = new Validator();
        $validator->set_value( 123 );
        $validator->set_attribute_path( 'address.street' );

        $validation_error = $validator->new_validation_error([
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_parameters' => [1, 2]
        ]);

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => null,
            'get_validation_parameters()' => [1, 2]
        ]);

        // Default validation_parameters

        $validator = new Validator();
        $validator->set_value( 123 );
        $validator->set_attribute_path( 'address.street' );

        $validation_error = $validator->new_validation_error([
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation'
        ]);

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'custom-validation',
            'get_validation_parameters()' => []
        ]);

    });


    $this->it( "adding new validation errors", function() {

        $validator = new Validator();

        $validator->add_error([
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
        ]);

        $validation_error = $validator->get_errors()[0];

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'custom-validation',
            'get_validation_parameters()' => [1, 2]
        ]);

        // Default value

        $validator = new Validator();
        $validator->set_value( 123 );
        $validator->set_attribute_path( 'address.street' );

        $validator->add_error([
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
        ]);

        $validation_error = $validator->get_errors()[0];

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'custom-validation',
            'get_validation_parameters()' => [1, 2]
        ]);

        // Default attribute_path

        $validator = new Validator();
        $validator->set_value( 123 );
        $validator->set_attribute_path( 'address.street' );

        $validator->add_error([
            'value' => 123,
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
        ]);

        $validation_error = $validator->get_errors()[0];

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'custom-validation',
            'get_validation_parameters()' => [1, 2]
        ]);

        // Default validation_name

        $validator = new Validator();
        $validator->set_value( 123 );
        $validator->set_attribute_path( 'address.street' );

        $validator->add_error([
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_parameters' => [1, 2]
        ]);

        $validation_error = $validator->get_errors()[0];

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => null,
            'get_validation_parameters()' => [1, 2]
        ]);

        // Default validation_parameters

        $validator = new Validator();
        $validator->set_value( 123 );
        $validator->set_attribute_path( 'address.street' );

        $validator->add_error([
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation'
        ]);

        $validation_error = $validator->get_errors()[0];

        $this->expect( $validation_error ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'custom-validation',
            'get_validation_parameters()' => []
        ]);

    });

});
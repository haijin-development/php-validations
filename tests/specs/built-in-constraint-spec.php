<?php

use Haijin\Validations\Validator;
use Haijin\Errors\Haijin_Error;

$spec->describe( "When using the built-in validation constraints", function() {

    /// Presence constraints

    $this->it( "is_present passes", function() {

        $object = 'a string';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_present();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_present fails", function() {

        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_present();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => null,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_present',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_present halts", function() {

        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_present();

            throw new Haijin_Error("is_present should halt its branch");
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => null,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_present',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "not_present passes", function() {

        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_present();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "not_present fails", function() {

        $object = 'a string';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_present();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 'a string',
            'get_attribute_path()' => '',
            'get_validation_name()' => 'not_present',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_optional fails", function() {

        $object = 'a string';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj ->is_optional() ->is_int();
        });


        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 'a string',
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_int',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_optional halts", function() {

        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_optional();

            throw new Haijin_Error( "is_optional should halt and this should not be executed" );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_optional sets a defualt value", function() {

        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_optional( "default value" );
        });

        $this->expect( $validator->get_value() ) ->to() ->equal( "default value" );

        $this->expect( [] ) ->to() ->equal( $validation_errors );
    });

    $this->it( "is_empty passes", function() {

        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_empty();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_empty fails", function() {

        $object = [ 123 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_empty();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [ 123 ],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_empty',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "not_empty passes", function() {

        $object = [ 'a string' ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_empty();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "not_empty fails", function() {

        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_empty();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'not_empty',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_defined passes", function() {

        $object = "a string";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );


        $object = [ 123 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );


        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_defined fails", function() {

        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => null,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_defined',
            'get_validation_parameters()' => []
        ]);


        $object = "";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => "",
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_defined',
            'get_validation_parameters()' => []
        ]);


        $object = " \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => " \t\n",
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_defined',
            'get_validation_parameters()' => []
        ]);


        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_defined',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_defined halts", function() {

        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();

            throw new Haijin_Error("is_defined should halt its branch");
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => null,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_defined',
            'get_validation_parameters()' => []
        ]);

    });

    /// String constraints

    $this->it( "is_blank passes", function() {

        $object = "";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_blank();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

        $object = " \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_blank();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_blank fails", function() {

        $object = "abc \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_blank();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => "abc \t\n",
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_blank',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "not_blank passes", function() {

        $object = "abc \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_blank();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "not_blank fails", function() {

        $object = "";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_blank();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => "",
            'get_attribute_path()' => '',
            'get_validation_name()' => 'not_blank',
            'get_validation_parameters()' => []
        ]);


        $object = " \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_blank();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => " \t\n",
            'get_attribute_path()' => '',
            'get_validation_name()' => 'not_blank',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "matches passes", function() {

        $object = '$10.00';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->matches('/[$]\d+\.\d\d/');
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "matches fails", function() {

        $object = '10.00';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->matches( '/[$]\d+\.\d\d/' );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => "10.00",
            'get_attribute_path()' => '',
            'get_validation_name()' => 'matches',
            'get_validation_parameters()' => [ '/[$]\d+\.\d\d/' ]
        ]);

    });

    /// Types constraints

    $this->it( "is_string passes", function() {

        $object = 'a string';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_string();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_string fails", function() {

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

    $this->it( "is_int passes", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_int();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_int fails", function() {

        $object = '1';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_int();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => '1',
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_int',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_float passes", function() {

        $object = 1.0;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_float();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_float fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_float();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_float',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_number passes", function() {

        $object = 1.0;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_number();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_number fails", function() {

        $object = '1.0';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_number();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => '1.0',
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_number',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_boolean passes", function() {

        $object = true;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_boolean();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_boolean fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_boolean();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_boolean',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_array passes", function() {

        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_array();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_array fails", function() {

        $object = '';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_array();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => '',
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_array',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "is_object passes", function() {

        $object = new stdclass();

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_object();
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_object fails", function() {

        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_object();
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_object',
            'get_validation_parameters()' => []
        ]);

    });

    /// Comparison

    $this->it( "equals passes", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '=', 1 );
            $obj->is( '==', 1 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "equals fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '=', 2 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => '==',
            'get_validation_parameters()' => [ 2 ]
        ]);


        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '==', 2 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => '==',
            'get_validation_parameters()' => [ 2 ]
        ]);

    });


    $this->it( "differs passes", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '!=', 2 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "differs fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '!=', 1 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => '!=',
            'get_validation_parameters()' => [ 1 ]
        ]);

    });

    $this->it( "lower passes", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '<', 2 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "lower fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '<', 0 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => '<',
            'get_validation_parameters()' => [ 0 ]
        ]);

    });

    $this->it( "lower or equal passes", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '<=', 1 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "lower or equal fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '<=', 0 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => '<=',
            'get_validation_parameters()' => [ 0 ]
        ]);

    });

    $this->it( "greater passes", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '>', 0 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "greater fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '>', 1 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => '>',
            'get_validation_parameters()' => [ 1 ]
        ]);

    });

    $this->it( "greater or equal passes", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '>=', 1 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "greater or equal fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '>=', 2 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => '>=',
            'get_validation_parameters()' => [ 2 ]
        ]);

    });

    $this->it( "equals with precision passes", function() {

        $object = 10.00;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '~', 9.99, 0.01 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "equals with precision fails", function() {

        $object = 10.00;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '~', 0.98, 0.01 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 10.00,
            'get_attribute_path()' => '',
            'get_validation_name()' => '~',
            'get_validation_parameters()' => [ 0.98, 0.01 ]
        ]);

    });

    $this->it( "differs with precision passes", function() {

        $object = 10.00;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '!~', 9.98, 0.01 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "differs with precision fails", function() {

        $object = 10.00;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '!~', 9.99, 0.01 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 10.00,
            'get_attribute_path()' => '',
            'get_validation_name()' => '!~',
            'get_validation_parameters()' => [ 9.99, 0.01 ]
        ]);

    });

    /// Inclussion

    $this->it( "has passes", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has( 3 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "has fails", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has( 4 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [ 1, 2, 3 ],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'has',
            'get_validation_parameters()' => [ 4 ]
        ]);

    });

    $this->it( "has_not passes", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_not( 4 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "has_not fails", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_not( 3 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [ 1, 2, 3 ],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'has_not',
            'get_validation_parameters()' => [ 3 ]
        ]);

    });

    $this->it( "has_all passes", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_all( [ 1, 3 ] );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "has_all fails", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_all( [ 1, 4 ] );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [ 1, 2, 3 ],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'has_all',
            'get_validation_parameters()' => [ [ 1, 4 ] ]
        ]);

    });

    $this->it( "has_any passes", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_any( [ 4, 3 ] );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "has_any fails", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_any( [ 4, 5 ] );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [ 1, 2, 3 ],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'has_any',
            'get_validation_parameters()' => [ [ 4, 5 ] ]
        ]);

    });

    $this->it( "has_none passes", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_none( [ 4, 5 ] );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "has_none fails", function() {

        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_none( [ 4, 1 ] );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [ 1, 2, 3 ],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'has_none',
            'get_validation_parameters()' => [ [ 4, 1 ] ]
        ]);

    });

    $this->it( "is_in passes", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_in( [ 1, 2 ] );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "is_in fails", function() {

        $object = 3;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_in( [ 1, 2 ] );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 3,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'is_in',
            'get_validation_parameters()' => [ [ 1, 2 ] ]
        ]);

    });

    $this->it( "not_in passes", function() {

        $object = 3;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_in( [ 1, 2 ] );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "not_in fails", function() {

        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_in( [ 1, 2 ] );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 1,
            'get_attribute_path()' => '',
            'get_validation_name()' => 'not_in',
            'get_validation_parameters()' => [ [ 1, 2 ] ]
        ]);

    });

    /// Length

    $this->it( "length passes", function() {

        $validator = new Validator();

        $validation_errors = $validator->validate( '', function($obj) {
            $obj->length( 0, 3 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );        $object = 'abc';


        $validation_errors = $validator->validate( 'abc', function($obj) {
            $obj->length( 1, 3 );
        });

        $this->expect( [] ) ->to() ->equal( $validation_errors );

    });

    $this->it( "length fails", function() {

        $validator = new Validator();

        $validation_errors = $validator->validate( '', function($obj) {
            $obj->length( 1, 3 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => '',
            'get_attribute_path()' => '',
            'get_validation_name()' => 'length',
            'get_validation_parameters()' => [ 1, 3 ]
        ]);


        $validator = new Validator();

        $validation_errors = $validator->validate( 'abcd', function($obj) {
            $obj->length( 1, 3 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 'abcd',
            'get_attribute_path()' => '',
            'get_validation_name()' => 'length',
            'get_validation_parameters()' => [ 1, 3 ]
        ]);


        $validator = new Validator();

        $validation_errors = $validator->validate( [], function($obj) {
            $obj->length( 1, 3 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'length',
            'get_validation_parameters()' => [ 1, 3 ]
        ]);


        $validator = new Validator();

        $validation_errors = $validator->validate( [ 1, 2, 3, 4 ], function($obj) {
            $obj->length( 1, 3 );
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );
        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => [ 1, 2, 3, 4 ],
            'get_attribute_path()' => '',
            'get_validation_name()' => 'length',
            'get_validation_parameters()' => [ 1, 3 ]
        ]);

    });

});
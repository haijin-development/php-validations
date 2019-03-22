<?php

use Haijin\Validations\Validator;

$spec->describe( "When validating nested attributes in validations of associative arrays", function() {

    $this->def( "increment_called_counter", function() {

        $this->called_counter += 1 ;
    });

    $this->it( "nested attributes passes", function() {

        $user = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $user, function($user) {
            $user->is_present();

            $user->attr('address', function($address) {
                $address ->is_present();

                $address->attr( 'street', function($street) {
                    $street->is_string();
                });
            });
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 0 );

    });

    $this->it( "nested attributes fails", function() {

        $user = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 123
            ]
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $user, function($user) {
            $user->is_present();

            $user->attr( 'address', function($address) {
                $address ->is_present();

                $address->attr('street', function($street) {
                    $street ->is_string();
                });
            });
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => 123,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'is_string',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "this binding is kept across nested attribute validations", function() {

        $user = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 123
            ]
        ];

        $validator = new Validator();

        $this->called_counter = 0;

        $validation_errors = $validator->validate( $user, function($user) {
            $this->increment_called_counter();

            $user->is_present();

            $user->attr( 'address', function($address) {
                $this->increment_called_counter();

                $address ->is_present();

                $address->attr('street', function($street) {
                    $this->increment_called_counter();

                    $street ->is_string();
                });
            });
        });

        $this->expect( $this->called_counter ) ->to() ->equal( 3 );

    });

    $this->it( "nested attribute halts its child attributes", function() {

        $user = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $validator = new Validator();

        $this->called_counter = 0;

        $validation_errors = $validator->validate( $user, function($user) {

            $user->attr( 'name', function($name) {
                $this->increment_called_counter();
            });

            $user->attr( 'last_name', function($last_name) {
                $this->increment_called_counter();
            });

            $user->attr( 'address', function($address) {
                $address->halt();

                $address->attr( 'street', function($street) {
                    $this->increment_called_counter();
                });
            });

        });

        $this->expect( $this->called_counter ) ->to() ->equal( 2 );

    });

    $this->it( "sibling branches are validated even when a branch fails", function() {

        $user = [
            'name' => 'Lisa',
            'last_name' => null,
            'address' => [
                'street' => 123
            ]
        ];

        $validator = new Validator();

        $this->called_counter = 0;

        $validation_errors = $validator->validate( $user, function($user) {

            $user->attr( 'name', function($name) {
                $this->increment_called_counter();
                $name->is_present();
            });

            $user->attr( 'last_name', function($last_name) {
                $this->increment_called_counter();
                $last_name->is_present();
            });

            $user->attr( 'address', function($address) {
                $address->is_present();

                $address->attr('street', function($street) {
                    $this->increment_called_counter();

                    $street->is_present();
                });
            });

        });

        $this->expect( $this->called_counter ) ->to() ->equal( 3 );

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => null,
            'get_attribute_path()' => 'last_name',
            'get_validation_name()' => 'is_present',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "nested attributes with no callables provided passes", function() {

        $user = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 'Evergreen 742'
            ]
        ];

        $validator = new Validator();

        $this->called_counter = 0;

        $validation_errors = $validator->validate( $user, function($user) {

            $user->attr( 'name' ) ->is_present();

            $user->attr( 'last_name' ) ->is_present();

            $user->attr( 'address', function($address) {
                $address->is_present();

                $address->attr('street' ) ->is_present();
            });

        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 0 );

    });

    $this->it( "nested attributes with no callables provided fails", function() {

        $user = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => null
            ]
        ];

        $validator = new Validator();

        $this->called_counter = 0;

        $validation_errors = $validator->validate( $user, function($user) {

            $user->attr( 'name' ) ->is_present();

            $user->attr( 'last_name' ) ->is_present();

            $user->attr( 'address', function($address) {
                $address->is_present();

                $address->attr('street' ) ->is_present();
            });

        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => null,
            'get_attribute_path()' => 'address.street',
            'get_validation_name()' => 'is_present',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "values are kept when nested attributes assigning values passes", function() {

        $user = [
            'name' => 'Lisa',
            'last_name' => 'Simpson',
            'address' => [
                'street' => 'Evergreen',
                'number' => '742'
            ]
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $user, function($user) {
            $user->is_present();

            $user->attr('address', function($address) {
                $address ->is_present();

                $address->attr( 'street', function($street) {
                    $street->is_string();
                });

                $address->attr( 'number', function($number) {
                    $number->is_string() ->as_int();
                });
            });
        });

        $this->expect( $validator->get_value() ) ->to() ->be() ->like([
            "address" => [
                "number" => function($value) {
                    $this->expect( $value ) ->to() ->be( "===" ) ->than( 742 );
                }
            ]
        ]);

    });

});
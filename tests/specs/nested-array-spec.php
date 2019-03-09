<?php

use Haijin\Validations\Validator;

$spec->describe( "When validating nested attributes in validations of indexed arrays", function() {

    $this->def( "increment_called_counter", function() {

        $this->called_counter += 1 ;

    });    

    $this->it( "nested array passes", function() {

        $purchase = [
            'items' => [
                [
                    'price' => 1.00
                ],
                [
                    'price' => 2.0
                ],
                [
                    'price' => 3.00
                ]
            ],
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $purchase, function($purchase) {
            $purchase->is_present();

            $purchase->attr('items', function($items) {
                $items ->is_present() ->is_array() ->not_empty();

                $items->each( function($each_item) {
                    $each_item->attr( 'price', function($street) {
                        $street ->is_present() ->is_float();
                    });
                });
            });
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 0 );

    });


    $this->it( "nested array fails", function() {

        $purchase = [
            'items' => [
                [
                    'price' => 1.00
                ],
                [
                    'price' => null
                ],
                [
                    'price' => 3.00
                ]
            ],
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $purchase, function($purchase) {
            $purchase->is_present();

            $purchase->attr('items', function($items) {
                $items ->is_present() ->is_array() ->not_empty();

                $items->each( function($each_item) {
                    $each_item->attr( 'price', function($street) {
                        $street ->is_present() ->is_float();
                    });
                });
            });
        });

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => null,
            'get_attribute_path()' => 'items.[1].price',
            'get_validation_name()' => 'is_present',
            'get_validation_parameters()' => []
        ]);

    });


    $this->it( "this binding is kept across nested attribute validations", function() {

        $purchase = [
            'items' => [
                [
                    'price' => 1.00
                ],
                [
                    'price' => 2.0
                ],
                [
                    'price' => 3.00
                ]
            ],
        ];

        $this->called_counter = 0;

        $validator = new Validator();

        $validation_errors = $validator->validate( $purchase, function($purchase) {
            $this->increment_called_counter();

            $purchase->is_present();

            $purchase->attr('items', function($items) {
                $this->increment_called_counter();

                $items ->is_present() ->is_array() ->not_empty();

                $items->each( function($each_item) {
                    $this->increment_called_counter();

                    $each_item->attr( 'price', function($street) {
                        $this->increment_called_counter();

                        $street ->is_present() ->is_float();
                    });
                });
            });
        });

        $this->expect( $this->called_counter ) ->to() ->equal( 8 );

    });


    $this->it( "sibling items are validated even when an item validation fails", function() {

        $purchase = [
            'items' => [
                [
                    'price' => 1.00
                ],
                [
                    'price' => null
                ],                
                [
                    'price' => 3.00
                ]
            ],
        ];

        $this->called_counter = 0;

        $validator = new Validator();

        $validation_errors = $validator->validate( $purchase, function($purchase) {
            $purchase->is_present();

            $purchase->attr('items', function($items) {
                $items ->is_present() ->is_array() ->not_empty();

                $items->each( function($each_item) {
                    $each_item->is_present();

                    $each_item->attr( 'price', function($street) {
                        $this->increment_called_counter();

                        $street ->is_present() ->is_float();
                    });
                });
            });
        }, $this);

        $this->expect( $this->called_counter ) ->to() ->equal( 3 );

        $this->expect( count($validation_errors) ) ->to() ->equal( 1 );

        $this->expect( $validation_errors[0] ) ->to() ->be() ->exactly_like([
            'get_value()' => null,
            'get_attribute_path()' => 'items.[1].price',
            'get_validation_name()' => 'is_present',
            'get_validation_parameters()' => []
        ]);

    });

    $this->it( "values are kept when nested attributes assigning values passes", function() {

        $purchase = [
            'items' => [
                [
                    'price' => '1.01'
                ],
                [
                    'price' => '2.01'
                ],
                [
                    'price' => '3.01'
                ]
            ],
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $purchase, function($purchase) {
            $purchase->is_present();

            $purchase->attr('items', function($items) {
                $items ->is_present() ->is_array() ->not_empty();

                $items->each( function($each_item) {
                    $each_item->attr( 'price', function($street) {
                        $street ->is_present() ->as_float();
                    });
                });
            });
        });

        $this->expect( $validator->get_value() ) ->to() ->be() ->exactly_like([
            'items' => [
                [
                    'price' => function($value) {
                        $this->expect( $value ) ->to() ->be( "===" ) ->than( 1.01 );
                    }
                ],
                [
                    'price' => function($value) {
                        $this->expect( $value ) ->to() ->be( "===" ) ->than( 2.01 );
                    }
                ],[
                    'price' => function($value) {
                        $this->expect( $value ) ->to() ->be( "===" ) ->than( 3.01 );
                    }
                ]
            ]
        ]);

    });

});
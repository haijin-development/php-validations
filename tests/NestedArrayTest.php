<?php

use PHPUnit\Framework\TestCase;

use Haijin\Validations\Validator;

/**
 * Tests the use of nested attributes in validations of indexed arrays.
 */
class NestedArrayTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    public function testNestedArrayPasses()
    {
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

        $this->assertEquals( 0, count( $validation_errors ) );
    }

    public function testNestedArrayFails()
    {
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

        $this->assertEquals( 1, count( $validation_errors ) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => 'items.[1].price',
            'validation_name' => 'is_present',
            'validation_parameters' => []
        ]);
    }

    public function testThisBindingIsKeptAcrossNestedAttributeValidations()
    {
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
        }, $this);

        $this->assertEquals( 8, $this->called_counter );
    }

    public function testSiblingItemsAreValidatedEvenWhenAnItemValidationFails()
    {
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

        $this->assertEquals( 3, $this->called_counter );

        $this->assertEquals( 1, count( $validation_errors ) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => 'items.[1].price',
            'validation_name' => 'is_present',
            'validation_parameters' => []
        ]);
    }

    public function increment_called_counter()
    {
        $this->called_counter += 1 ;
    }
}
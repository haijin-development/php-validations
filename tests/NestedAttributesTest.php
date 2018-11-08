<?php

use PHPUnit\Framework\TestCase;

use Haijin\Validations\Validator;

/**
 * Tests the use of nested attributes in validations of objects and associative arrays.
 */
class NestedAttributesTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    public function testNestedAttributesPasses()
    {
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

        $this->assertEquals( $validation_errors, [] );
    }

    public function testNestedAttributesFails()
    {
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

        $this->assertEquals( 1, count( $validation_errors ) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'is_string',
            'validation_parameters' => []
        ]);
    }

    public function testThisBindingIsKeptAcrossNestedAttributeValidations()
    {
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
        }, $this);

        $this->assertEquals( 3, $this->called_counter );
    }

    public function testNestedAttributeHaltsItsChildAttributes()
    {
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

        }, $this);

        $this->assertEquals( 2, $this->called_counter );
    }

    public function testSiblingBranchesAreValidatedEvenWhenABranchFails()
    {
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

        }, $this);

        $this->assertEquals( 3, $this->called_counter );

        $this->assertEquals( 1, count( $validation_errors ) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => 'last_name',
            'validation_name' => 'is_present',
            'validation_parameters' => []
        ]);
    }

    public function testNestedAttributesWithNoClosuresProvidedPasses()
    {
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

        }, $this);

        $this->assertEquals( 0, count( $validation_errors ) );
    }

    public function testNestedAttributesUsingArrayAccessPasses()
    {
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

            $user[ 'name' ] ->is_present();

            $user[ 'last_name' ] ->is_present();

            $user[ 'address' ] ->eval( function($address) {
                $address->is_present();

                $address->attr('street' ) ->is_present();
            });

        }, $this);

        $this->assertEquals( 0, count( $validation_errors ) );
    }

    public function testNestedAttributesWithNoClosuresProvidedFails()
    {
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

        }, $this);

        $this->assertEquals( 1, count( $validation_errors ) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => 'address.street',
            'validation_name' => 'is_present',
            'validation_parameters' => []
        ]);
    }

    public function testNestedAttributesWithArrayAccessFails()
    {
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

            $user[ 'name' ] ->is_present();

            $user['last_name' ] ->is_present();

            $user[ 'address' ] ->eval( function($address) {
                $address->is_present();

                $address->attr('street' ) ->is_present();
            });

        }, $this);

        $this->assertEquals( 1, count( $validation_errors ) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => 'address.street',
            'validation_name' => 'is_present',
            'validation_parameters' => []
        ]);
    }

    public function increment_called_counter()
    {
        $this->called_counter += 1 ;
    }
}
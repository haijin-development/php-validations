<?php

use PHPUnit\Framework\TestCase;

use Haijin\Validations\Validator;

/**
 * Tests the use of custom closures to perform validations.
 */
class CustomValidationClosuresTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    public function testValidationBlockPasses()
    {
        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 7
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( function($validator) {
                $validator->set_validation_name( 'invalid-total' );

                $prices = $validator->get_value();

                if( $prices['price_1'] + $prices['price_2'] == $prices['total'] )
                    return;

                $validator->add_error();
            });

        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testValidationBlockFails()
    {
        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 6
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->validate_with( function($validator) {
                $validator->set_validation_name( 'invalid-total' );

                $prices = $validator->get_value();

                if( $prices['price_1'] + $prices['price_2'] == $prices['total'] )
                    return;

                $validator->add_error();
            });
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => $object,
            'attribute_path' => '',
            'validation_name' => 'invalid-total',
            'validation_parameters' => []
        ]);
    }
}
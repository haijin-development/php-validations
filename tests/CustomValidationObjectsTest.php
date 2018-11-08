<?php

use PHPUnit\Framework\TestCase;

use Haijin\Validations\Validator;

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

/**
 * Tests the use of custom Validator subclass instance to perform validations.
 */
class CustomValidationObjectTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    public function testValidationInstacePasses()
    {
        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 7
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( new CustomValidation() );

        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testValidationInstaceFails()
    {
        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 6
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( new CustomValidation() );

        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => $object,
            'attribute_path' => '',
            'validation_name' => 'invalid-total',
            'validation_parameters' => []
        ]);
    }

    public function testValidationClassPasses()
    {
        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 7
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( 'CustomValidation' );

        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testValidationClassFails()
    {
        $object = [
            'price_1' => 3,
            'price_2' => 4,
            'total' => 6
        ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {

            $obj->validate_with( 'CustomValidation' );

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
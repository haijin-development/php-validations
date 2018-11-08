<?php

use PHPUnit\Framework\TestCase;

use Haijin\Validations\Validator;

/**
 * Tests the binding to $this pseudo-variable when using the function validate( $validation_closure, [$this] ).
 */
class ContextBindingDuringValidationsTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    public function testBindsToTheValidationObjectByDefault()
    {
        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_string();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 123,
            'attribute_path' => '',
            'validation_name' => 'is_string',
            'validation_parameters' => []
        ]);
    }

    public function testBindsToTheCurrentClass()
    {
        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $this->validate_method_in_calling_class_context( $obj );
        }, $this);

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 123,
            'attribute_path' => '',
            'validation_name' => 'is_string',
            'validation_parameters' => []
        ]);
    }

    public function validate_method_in_calling_class_context( $obj )
    {
        $obj->is_string();
    }
}
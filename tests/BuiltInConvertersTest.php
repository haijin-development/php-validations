<?php

use PHPUnit\Framework\TestCase;

use Haijin\Validations\Validator;

/**
 * Tests the use of the built converters in validations.
 */
class BuiltInConvertersTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    public function testAsString()
    {
        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->to_string();
        });

        $this->assertSame( $validator->get_value(), "123" );
    }

    public function testAsInteger()
    {
        $object = "123";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->as_int();
        });

        $this->assertSame( $validator->get_value(), 123 );
    }

    public function testAsFloat()
    {
        $object = "1.00";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->as_float();
        });

        $this->assertSame( $validator->get_value(), 1.00 );
    }

    public function testAsBoolean()
    {
        $object = "true";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->as_boolean();
        });

        $this->assertSame( $validator->get_value(), true );
    }
}
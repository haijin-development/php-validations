<?php

use PHPUnit\Framework\TestCase;

use Haijin\Validations\Validator;
use Haijin\Validations\ValidationErrorException;

/**
 * Tests the protocol of the Validator object.
 */
class ValidationProtocolTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    // Testing accessors

    public function testAccessingTheValidatedValue()
    {
        $validator = new Validator( null );
        $validator->set_value( 123 );

        $this->assertEquals( 123, $validator->get_value() );
    }

    public function testAccessingTheAttributePath()
    {
        $validator = new Validator( 123 );
        $validator->set_attribute_path( 'address.street' );

        $this->assertEquals( 'address.street', $validator->get_attribute_path()->to_string() );
    }

    // Test adding validation errors

    public function testCreatingValidationErrors()
    {
        $validator = new Validator();

        $validation_error = $validator->new_validation_error([
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
        ]);

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
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

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
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

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
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

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => null,
            'validation_parameters' => [1, 2]
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

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => []
        ]);
    }


    public function testAddingNewValidationErrors()
    {
        $validator = new Validator();

        $validator->add_error([
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
        ]);

        $validation_error = $validator->get_errors()[0];

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
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

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
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

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => [1, 2]
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

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => null,
            'validation_parameters' => [1, 2]
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

        $this->assertValidationError( $validation_error, [
            'value' => 123,
            'attribute_path' => 'address.street',
            'validation_name' => 'custom-validation',
            'validation_parameters' => []
        ]);
    }
}
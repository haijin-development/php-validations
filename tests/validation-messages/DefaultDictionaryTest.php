<?php

use PHPUnit\Framework\TestCase;

use Haijin\Tools\AttributePath;
use Haijin\Validations\ValidationError;
use Haijin\Validations\ValidationErrorsDictionary;

/**
 * Tests the default validation errors messages.
 */
class DefaultDictionaryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->dictionary = ValidationErrorsDictionary::new_default();
    }

    /**
     * Tests the formatted message for the is_present validation.
     */
    public function testIsPresentMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_present', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be present.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the not_present validation.
     */
    public function testNotPresentMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_present', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not be present.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_empty validation.
     */
    public function testIsEmptyMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_empty', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be empty.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the not_empty validation.
     */
    public function testNotEmptyMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_empty', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not be empty.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_defined validation.
     */
    public function testIsDefinedMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_defined', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be defined.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the not_defined validation.
     */
    public function testNotDefinedMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_defined', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not be defined.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_blank validation.
     */
    public function testIsBlankMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_blank', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be blank.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the not_blank validation.
     */
    public function testNotBlankMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_blank', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not be blank.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the matches validation.
     */
    public function testMatchesMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'matches', [ '/expression/' ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must match '/expression/'.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_string validation.
     */
    public function testIsStringMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_string', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be a string.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_int validation.
     */
    public function testIsIntegerMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_int', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be an int.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_float validation.
     */
    public function testIsFloatMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_float', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be a float.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_number validation.
     */
    public function testIsNumberMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_number', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be a number.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_boolean validation.
     */
    public function testIsBooleanMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_boolean', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be a boolean.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_array validation.
     */
    public function testIsArrayMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_array', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be an array.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_object validation.
     */
    public function testIsObjectMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_object', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be an object.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the == validation.
     */
    public function testEqualsMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '==', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must equal 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the != validation.
     */
    public function testNotEqualMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '!=', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not equal 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the < validation.
     */
    public function testLowerMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '<', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be < 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the <= validation.
     */
    public function testLowerOrEqualMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '<=', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be <= 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the > validation.
     */
    public function testGreaterMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '>', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be > 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the >= validation.
     */
    public function testGreaterOrEqualMessage()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '>=', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be >= 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the ~ validation.
     */
    public function testEqualWithPrecision()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '~', [ 2, 0.01 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be ~ 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the !~ validation.
     */
    public function testNotEqualWithPrecision()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '!~', [ 2, 0.01 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be !~ 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the has validation.
     */
    public function testHas()
    {
        $validation_error = new ValidationError( [], new AttributePath( 'address.street' ), 'has', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have the element 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the has_not validation.
     */
    public function testHasNot()
    {
        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'has_not', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not have the element 2.";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the has_all validation.
     */
    public function testHasAll()
    {
        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'has_all', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have all the elements [2, 3].";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the has_any validation.
     */
    public function testHasAny()
    {
        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'has_any', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have any of the elements [2, 3].";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the has_none validation.
     */
    public function testHasNone()
    {
        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'has_none', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have none of the elements [2, 3].";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the is_in validation.
     */
    public function testIsIn()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_in', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be one of [2, 3].";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the not_in validation.
     */
    public function testNotIn()
    {
        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_in', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be none of [2, 3].";
        $this->assertEquals( $expected_message, $message );
    }

    /**
     * Tests the formatted message for the length validation.
     */
    public function testLength()
    {
        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have a length between 2 and 3.";
        $this->assertEquals( $expected_message, $message );



        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [null, 3] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have a maximum length of 3.";
        $this->assertEquals( $expected_message, $message );


        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, null] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have a minimum length of 2.";
        $this->assertEquals( $expected_message, $message );
    }
}
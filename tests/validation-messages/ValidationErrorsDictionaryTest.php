<?php

use PHPUnit\Framework\TestCase;

use Haijin\Tools\AttributePath;
use Haijin\Validations\ValidationError;
use Haijin\Validations\ValidationErrorsDictionary;

/**
 * Tests the default validation errors messages.
 */
class ValidationErrorsDictionaryTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    public function setUp()
    {
        parent::setUp();

        $this->dictionary = new ValidationErrorsDictionary();
    }

    public function testRaisesAnErrorIfNoMessageIsFound()
    {
        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );

        $this->expectExactExceptionRaised(
            'Haijin\Validations\ValidationMessageNotFoundException',
            function() use($validation_error) {
                $this->dictionary->message_for( $validation_error );
            },
            function($error) use($validation_error) {
                $this->assertSame( $validation_error, $error->get_validation_error() );
                $this->assertEquals( 'No message formatter was found for the ValidationError "length"', $error->getMessage() );
            }
        );
    }

    public function testDefaultMessage()
    {
        $this->dictionary->define( function() {
            $this->default( function($validation_error) {
                return "Invalid value";
            });
        });

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );
        $message = $this->dictionary->message_for( $validation_error );

        $this->assertEquals( "Invalid value", $message );
    }

    public function testDroppingDefaultMessage()
    {
        $this->dictionary->define( function() {
            $this->default( function($validation_error) {
                return "Invalid value";
            });
        });

        $this->dictionary->drop_default();

        $this->expectExactExceptionRaised(
            'Haijin\Validations\ValidationMessageNotFoundException',
            function() {
                $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );
                $this->dictionary->message_for( $validation_error );
            },
            function($error) {
                $this->assertEquals( 'No message formatter was found for the ValidationError "length"', $error->getMessage() );
            }
        );
    }

    public function testHasDefaultMessage()
    {
        $this->assertEquals( false, $this->dictionary->has_default() );

        $this->dictionary->default( function($validation_error) {
            return "Invalid value";
        });

        $this->assertEquals( true, $this->dictionary->has_default() );

        $this->dictionary->drop_default();

        $this->assertEquals( false, $this->dictionary->has_default() );
    }

    public function testValidationMessage()
    {
        $this->dictionary->define( function() {
            $this->at_validation( "length", function($validation_error) {
                return "Invalid length";
            });
        });

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );
        $message = $this->dictionary->message_for( $validation_error );

        $this->assertEquals( "Invalid length", $message );
    }

    public function testDroppingValidationMessage()
    {
        $this->dictionary->define( function() {
            $this->at_validation( "length", function($validation_error) {
                return "Invalid length";
            });
        });

        $this->dictionary->define( function() {
            $this->drop_validation( "length" );
        });

        $this->expectExactExceptionRaised(
            'Haijin\Validations\ValidationMessageNotFoundException',
            function() {
                $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );
                $this->dictionary->message_for( $validation_error );
            },
            function($error) {
                $this->assertEquals( 'No message formatter was found for the ValidationError "length"', $error->getMessage() );
            }
        );
    }

    public function testValidationMessageOverridesDefaultMessage()
    {
        $this->dictionary->define( function() {
            $this->at_validation( "length", function($validation_error) {
                return "Invalid length";
            });

            $this->default( function($validation_error) {
                return "Invalid value";
            });
        });

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );
        $message = $this->dictionary->message_for( $validation_error );

        $this->assertEquals( "Invalid length", $message );
    }
}
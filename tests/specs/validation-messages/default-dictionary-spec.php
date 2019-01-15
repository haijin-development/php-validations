<?php

use Haijin\Tools\AttributePath;
use Haijin\Validations\ValidationError;
use Haijin\Validations\ValidationErrorsDictionary;

$spec->describe( "When using the default ValidationErrorsDictionary", function() {

    $this->let( "dictionary", function() {

        return ValidationErrorsDictionary::new_default();

    });

    $this->it( "the formatted message for the is_present validation is defined", function() {

        $validation_error =
            new ValidationError( 1, new AttributePath( 'address.street' ), 'is_present', [] );

        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be present.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the not_present validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_present', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not be present.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_empty validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_empty', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be empty.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the not_empty validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_empty', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not be empty.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_defined validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_defined', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be defined.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the not_defined validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_defined', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not be defined.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_blank validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_blank', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be blank.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the not_blank validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_blank', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not be blank.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the matches validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'matches', [ '/expression/' ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must match '/expression/'.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_string validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_string', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be a string.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_int validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_int', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be an int.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_float validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_float', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be a float.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_number validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_number', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be a number.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_boolean validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_boolean', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be a boolean.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_array validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_array', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be an array.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_object validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_object', [] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be an object.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the == validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '==', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must equal 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the != validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '!=', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not equal 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the < validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '<', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be < 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the <= validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '<=', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be <= 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the > validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '>', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be > 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the >= validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '>=', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be >= 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the ~ validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '~', [ 2, 0.01 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be ~ 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the !~ validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), '!~', [ 2, 0.01 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be !~ 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the has validation is defined", function() {

        $validation_error = new ValidationError( [], new AttributePath( 'address.street' ), 'has', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have the element 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the has_not validation", function() {

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'has_not', [ 2 ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must not have the element 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the has_all validation is defined", function() {

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'has_all', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have all the elements [2, 3].";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the has_any validation is defined", function() {

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'has_any', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have any of the elements [2, 3].";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the has_none validation is defined", function() {

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'has_none', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have none of the elements [2, 3].";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the is_in validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'is_in', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be one of [2, 3].";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the not_in validation is defined", function() {

        $validation_error = new ValidationError( 1, new AttributePath( 'address.street' ), 'not_in', [ [ 2, 3 ] ] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must be none of [2, 3].";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

    $this->it( "the formatted message for the length validation is defined", function() {

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have a length between 2 and 3.";
        $this->expect( $message ) ->to() ->equal( $expected_message );



        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [null, 3] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have a maximum length of 3.";
        $this->expect( $message ) ->to() ->equal( $expected_message );


        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, null] );
        $message = $this->dictionary->message_for( $validation_error );

        $expected_message = "The attribute 'address.street' must have a minimum length of 2.";
        $this->expect( $message ) ->to() ->equal( $expected_message );

    });

});
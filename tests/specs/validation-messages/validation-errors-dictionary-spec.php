<?php

use Haijin\Tools\AttributePath;
use Haijin\Validations\ValidationError;
use Haijin\Validations\ValidationErrorsDictionary;

$spec->describe( "A ValidationErrorsDictionary", function() {

    $this->let( "dictionary", function() {

        return new ValidationErrorsDictionary();

    });

    $this->it( "RaisesAnErrorIfNoMessageIsFound", function() {

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );

        $this->expect( function() use($validation_error) {

            $this->dictionary->message_for( $validation_error );

        }) ->to() ->raise(
            'Haijin\Validations\ValidationMessageNotFoundException',
            function($error) use($validation_error) {

                $this->expect( $error->get_validation_error() ) ->to() ->be( "===" )
                    ->than( $validation_error );

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( 'No message formatter was found for the ValidationError "length"' );

        });

    });

    $this->it( "DefaultMessage", function() {

        $this->dictionary->define( function() {

            $this->default( function($validation_error) {
                return "Invalid value";
            });

        });

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );

        $message = $this->dictionary->message_for( $validation_error );

        $this->expect( $message  ) ->to() ->equal( "Invalid value" );

    });

    $this->it( "DroppingDefaultMessage", function() {

        $this->dictionary->define( function() {

            $this->default( function($validation_error) {
                return "Invalid value";
            });

        });

        $this->dictionary->drop_default();

        $this->expect( function() {

            $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );

            $this->dictionary->message_for( $validation_error );

        }) ->to() ->raise(
            'Haijin\Validations\ValidationMessageNotFoundException',
            function($error) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( 'No message formatter was found for the ValidationError "length"' );
        });

    });

    $this->it( "HasDefaultMessage", function() {

        $this->expect( $this->dictionary->has_default() ) ->to() ->be() ->false();

        $this->dictionary->default( function($validation_error) {
            return "Invalid value";
        });

        $this->expect( $this->dictionary->has_default() ) ->to() ->be() ->true();

        $this->dictionary->drop_default();

        $this->expect( $this->dictionary->has_default() ) ->to() ->be() ->false();

    });

    $this->it( "ValidationMessage", function() {

        $this->dictionary->define( function() {

            $this->at_validation( "length", function($validation_error) {
                return "Invalid length";
            });

        });

        $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );

        $message = $this->dictionary->message_for( $validation_error );

        $this->expect( $message  ) ->to() ->equal( "Invalid length" );

    });

    $this->it( "DroppingValidationMessage", function() {

        $this->dictionary->define( function() {

            $this->at_validation( "length", function($validation_error) {
                return "Invalid length";
            });

        });

        $this->dictionary->define( function() {
            $this->drop_validation( "length" );
        });

        $this->expect( function() {

            $validation_error = new ValidationError( [ 2 ], new AttributePath( 'address.street' ), 'length', [2, 3] );

            $this->dictionary->message_for( $validation_error );

        }) ->to() ->raise(
            'Haijin\Validations\ValidationMessageNotFoundException',
            function($error) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( 'No message formatter was found for the ValidationError "length"' );

        });

    });

    $this->it( "ValidationMessageOverridesDefaultMessage", function() {

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

        $this->expect( $message  ) ->to() ->equal( "Invalid length" );

    });

});
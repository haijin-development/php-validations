<?php

use Haijin\Attribute_Path;
use Haijin\Validations\Validation_Error;
use Haijin\Validations\Messages\Validation_Messages_Dictionary;
use Haijin\Validations\Messages\Validation_Message_Not_Found_Exception;

$spec->describe( "A Validation_Messages_Dictionary", function() {

    $this->let( "dictionary", function() {

        return new Validation_Messages_Dictionary();

    });

    $this->it( "RaisesAnErrorIfNoMessageIsFound", function() {

        $validation_error = new Validation_Error( [ 2 ], new Attribute_Path( 'address.street' ), 'length', [2, 3] );

        $this->expect( function() use($validation_error) {

            $this->dictionary->message_for( $validation_error );

        }) ->to() ->raise(
            Validation_Message_Not_Found_Exception::class,
            function($error) use($validation_error) {

                $this->expect( $error->get_validation_error() ) ->to() ->be( "===" )
                    ->than( $validation_error );

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( 'No message formatter was found for the Validation_Error "length"' );

        });

    });

    $this->it( "DefaultMessage", function() {

        $this->dictionary->define( function($dict) {

            $dict->default( function($validation_error) {
                return "Invalid value";
            });

        });

        $validation_error = new Validation_Error( [ 2 ], new Attribute_Path( 'address.street' ), 'length', [2, 3] );

        $message = $this->dictionary->message_for( $validation_error );

        $this->expect( $message  ) ->to() ->equal( "Invalid value" );

    });

    $this->it( "DroppingDefaultMessage", function() {

        $this->dictionary->define( function($dict) {

            $dict->default( function($validation_error) {
                return "Invalid value";
            });

        });

        $this->dictionary->drop_default();

        $this->expect( function() {

            $validation_error = new Validation_Error( [ 2 ], new Attribute_Path( 'address.street' ), 'length', [2, 3] );

            $this->dictionary->message_for( $validation_error );

        }) ->to() ->raise(
            Validation_Message_Not_Found_Exception::class,
            function($error) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( 'No message formatter was found for the Validation_Error "length"' );
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

        $this->dictionary->define( function($dict) {

            $dict->at_validation( "length", function($validation_error) {
                return "Invalid length";
            });

        });

        $validation_error = new Validation_Error( [ 2 ], new Attribute_Path( 'address.street' ), 'length', [2, 3] );

        $message = $this->dictionary->message_for( $validation_error );

        $this->expect( $message  ) ->to() ->equal( "Invalid length" );

    });

    $this->it( "DroppingValidationMessage", function() {

        $this->dictionary->define( function($dict) {

            $dict->at_validation( "length", function($validation_error) {
                return "Invalid length";
            });

        });

        $this->dictionary->define( function($dict) {
            $dict->drop_validation( "length" );
        });

        $this->expect( function() {

            $validation_error = new Validation_Error( [ 2 ], new Attribute_Path( 'address.street' ), 'length', [2, 3] );

            $this->dictionary->message_for( $validation_error );

        }) ->to() ->raise(
            Validation_Message_Not_Found_Exception::class,
            function($error) {

                $this->expect( $error->getMessage() ) ->to()
                    ->equal( 'No message formatter was found for the Validation_Error "length"' );

        });

    });

    $this->it( "ValidationMessageOverridesDefaultMessage", function() {

        $this->dictionary->define( function($dict) {

            $dict->at_validation( "length", function($validation_error) {
                return "Invalid length";
            });

            $dict->default( function($validation_error) {
                return "Invalid value";
            });

        });

        $validation_error = new Validation_Error( [ 2 ], new Attribute_Path( 'address.street' ), 'length', [2, 3] );

        $message = $this->dictionary->message_for( $validation_error );

        $this->expect( $message  ) ->to() ->equal( "Invalid length" );

    });

});
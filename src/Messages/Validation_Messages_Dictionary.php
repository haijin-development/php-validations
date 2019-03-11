<?php

namespace Haijin\Validations\Messages;

use Haijin\File_Path;

/**
 * Provides a nice DSL to define error messages for Validation_Errors.
 *
 * Example
 *
 *      /// Create a new Validation_Messages_Dictionary
 *
 *      // Create a new Validation_Messages_Dictionary
 *
 *      $validation_messages_dictionary = new Validation_Messages_Dictionary();
 *
 *      // and optionally define default messages in it
 *
 *      $validation_messages_dictionary->with_default_messages();
 *
 *      // Define a default validation message when a more specific message is not found
 *
 *      $validation_messages_dictionary->define( function($messages) {
 *
 *          $messages->default( function($validation_error) {
 *              return "The attribute '{$validation_error->get_attribute_name()}' is not valid.";
 *          });
 *
 *      });
 *
 *      // Get the validation message for each validation error
 *
 *      foreach( $validation_errors as $error ) {
 *          print( $validation_messages_dictionary->message_for( $error ) );
 *          print "\n";
 *      }
 *
 *      /// Define more specific messages
 *
 *      $validation_messages_dictionary->define( function($messages) {
 *
 *          $messages->at_validation( 'not_blank', function($validation_error) {
 *              return "The attribute '{$validation_error->get_attribute_name()}' must contain visible characters.";
 *          });
 *
 *          $messages->at_validation( 'length', function($validation_error) {
 *              $params = $validation_error->get_validation_parameters();
 *
 *              return "The attribute '{$validation_error->get_attribute_name()}' length must be in the range ({$params[0]}, {$params[1]})";
 *          });
 *      });
 *
 *      // Get the validations messages
 *
 *      foreach( $validation_messages_dictionary as $error ) {
 *          print( $errors_dictionary->message_for( $error ) );
 *          print "\n";
 *      }
 */
class Validation_Messages_Dictionary
{
    /**
     * A map from validation names to callables that format Validation_Errors.
     */
    protected $message_formatters_map;

    /**
     * An optional callable acting as a default formatter when no other formatter is found for a Validation_Error.
     */
    protected $default_message_formatter;

    /// Initializing

    /**
     * Initializes the instance.
     */
    public function __construct()
    {
        $this->message_formatters_map = [];
        $this->default_message_formatter = null;
    }

    /// Definition

    /**
     * Defines the formatters for Validation_Errors using a DSL.
     *
     * @param callable $definition_callable A callable using the DSL to define 
     *      the formatting of the ValidationsError messages.
     *
     * @return Validation_Messages_Dictionary Returns $this instance.
     */
    public function define($definition_callable)
    {
        $definition_callable( $this );

        return $this;
    }

    /**
     * Defines in $this dictionary the default message formatters.
     *
     * @return Validation_Messages_Dictionary Returns $this instance.
     */
    public function with_default_messages()
    {
        $messages = $this;

        require __DIR__ . "/defualt-validations-messages.php";

        return $this;
    }


    /**
     * Defines a callable to format a Validation_Error for validations of type $validation_name.
     *
     * @param string $validation_name The name of the validations which messages will
     *      be formatted using the $formatter_callable.
     * @param callable $formatter_callable A callable that returns the formatted message.
     *
     * @return Validation_Messages_Dictionary Returns $this instance.
     */
    public function at_validation($validation_name, $formatter_callable)
    {
        $this->message_formatters_map[ $validation_name ] = $formatter_callable;

        return $this;
    }

    /**
     * Drops the callable that formats a Validation_Error for validations of type $validation_name.
     *
     * @param string $validation_name The name of the validations to drop its
     *      formatter callable.
     *
     * @return Validation_Messages_Dictionary Returns $this instance.
     */
    public function drop_validation($validation_name)
    {
        unset( $this->message_formatters_map[ $validation_name ] );

        return $this;
    }

    /**
     * Defines a callable to format Validation_Error by default, unless a more specific
     *  callable is found for a Validation_Error.
     *
     * @param callable $formatter_callable A callable that returns the formatted message.
     *
     * @return Validation_Messages_Dictionary Returns $this instance.
     */
    public function default($formatter_callable)
    {
        $this->default_message_formatter = $formatter_callable;

        return $this;
    }

    /**
     * Drops the callable that formats a Validation_Error by default.
     *
     * @return Validation_Messages_Dictionary Returns $this instance.
     */
    public function drop_default()
    {
        $this->default_message_formatter = null;

        return $this;
    }

    /// Asking

    /**
     * Returns true if $this dictionary has a default callable defined, false if not.
     *
     * @return bool Returns true if this instance has a default callable defined, false if not.
     */
    public function has_default()
    {
        return $this->default_message_formatter !== null;
    }

    /**
     * Returns true if $this dictionary has a message formatter defined for a $validation_name, false if not.
     *
     * @param string $validation_name The name of the validation.
     *
     * @return bool Returns true if $this dictionary has a message formatter for a $validation_name, false if not.
     */
    public function has_message_at($validation_name)
    {
        return array_key_exists( $validation_name, $this->message_formatters_map );
    }

    /**
     * Returns true if $this dictionary has a message formatter for a Validation_Error, false if not.
     *
     * @param Validation_Error $validation_name The Validation_Error.
     *
     * @return bool Returns true if $this dictionary has a message formatter for the $validation_error, false if not.
     */
    public function has_message_for($validation_error)
    {
        if( $this->default_message_formatter !== null ) {
            return true;
        }

        return $this->has_message_at( $validation_error->get_validation_name() );
    }

    /// Accessing

    /**
     * Returns the defined callable for a $validation_name.
     *
     * @param string $validation_name The name of the validation to look up for its callable.
     *
     * @return callable The callable defined for the $validation_name.
     */
    protected function message_formatter_at($validation_name)
    {
        return $this->message_formatters_map[ $validation_name ];
    }

    /**
     * Returns a callable to format an error message for a Validation_Error.
     *
     * If $this dictionary has a callable for the Validation_Error::validation_name 
     * that callable is returned. If not the default callable is returned.
     *
     * @param Validation_Error $validation_error The Validation_Error to look up for
     *      its formatter callable.
     *
     * @return callable The callable found for the Validation_Error.
     */
    protected function callable_for($validation_error)
    {
        $validation_name = $validation_error->get_validation_name();

        if( $this->has_message_at( $validation_name ) ) {
            return $this->message_formatter_at( $validation_name );
        }

        return $this->default_message_formatter;
    }

    /**
     * Returns a formatted message string for a Validation_Error.
     * If no formatter is found for the Validation_Error a Validation_Message_Not_Found_Exception is raised.
     *
     * @param Validation_Error $validation_error The Validation_Error to be formatted to a end user message string.
     *
     * @return string The message string to the end user for the Validation_Error.
     */
    public function message_for($validation_error)
    {
        $this->validate_message_presence_for( $validation_error );

        return $this->callable_for( $validation_error )( $validation_error );
    }

    /// Errors

    /**
     * Validates that a message exists for a given Validation_Error.
     * Raises a Validation_Message_Not_Found_Exception if not.
     *
     * @param Validation_Error $validation_error The Validation_Error to validate for message presence.
     */
    public function validate_message_presence_for($validation_error)
    {
        if( $this->has_message_for( $validation_error ) )
            return;

        $this->raise_message_not_found_error( $validation_error );
    }

    /**
     * Raises a Validation_Message_Not_Found_Exception.
     *
     * @param Validation_Error $validation_error The Validation_Error that is missing a formatter callable.
     */
    protected function raise_message_not_found_error($validation_error)
    {
        throw new Validation_Message_Not_Found_Exception( $validation_error );
    }
}
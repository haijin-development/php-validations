<?php

namespace Haijin\Validations;

use  Haijin\Instantiator\Create;
use  Haijin\File_Path;
use  Haijin\Closure_Context;

/**
 * Provides a nice DSL to define error messages for ValidationErrors.
 *
 * Example
 *
 *      /// Create a new Validation_Errors_Dictionary
 *
 *      // Use a dictionary with default messages
 *
 *      $errors_dictionary = Validation_Errors_Dictionary::new_default();
 *
 *      // or create a new one
 *
 *      $errors_dictionary = new Validation_Errors_Dictionary();
 *
 *      /// Define a default validation message
 *
 *      $errors_dictionary->define( function($messages) {
 *
 *          $messages->default( function($validation_error) {
 *              return "The attribute '{$validation_error->get_attribute_name()}' is not valid.";
 *          });
 *
 *      });
 *
 *      // Get the validations messages
 *
 *      foreach( $validation_errors as $error ) {
 *          print( $errors_dictionary->message_for( $error ) );
 *          print "\n";
 *      }
 *
 *      /// Define more specific messages
 *
 *      $errors_dictionary->define( function($messages) {
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
 *      foreach( $validation_errors as $error ) {
 *          print( $errors_dictionary->message_for( $error ) );
 *          print "\n";
 *      }
 */
class Validation_Errors_Dictionary
{
    /// Class methods

        /// Creating instances

    /**
     * An object to initialize default instances with validation message formatters.
     */
    static protected $dictionary_initializer = null;

    /**
     * Returns a new instance of a Validation_Errors_Dictionary initalized with default message formatters.
     */
    static public function new_default()
    {
        return self::$dictionary_initializer->initialize( Create::a( self::class )->with() );
    }

        /// Accessing

    /**
     * Returns the global dictionary initializer used to initialize instances of Validation_Errors_Dictionary when
     * calling Validation_Errors_Dictionary::default.
     */
    static public function get_dictionary_initializer()
    {
        return self::$dictionary_initializer;
    }

    /**
     * Sets the global dictionary initializer used to initialize instances of Validation_Errors_Dictionary when
     * calling Validation_Errors_Dictionary::default.
     */
    static public function set_dictionary_initializer($dictionary_initializer)
    {
        self::$dictionary_initializer = $dictionary_initializer;
    }

    /// Instance methods

    /**
     * A map from validation names to Closure_Contexts that format ValidationErrors.
     */
    protected $message_formatters_map;

    /**
     * An optional Closure_Context acting as a default formatter when no other formatter is found for a Validation_Error.
     */
    protected $default_message_formatter;

    /**
     * The binding to '$this' pseudo-variable when evaluating each formatter Closure_Context.
     */
    protected $binding;

    /**
     * Initializes the instance.
     */
    public function __construct()
    {
        $this->message_formatters_map = [];
        $this->default_message_formatter = null;
        $this->binding = $this;
    }

    /// Definition

    /**
     * Sets the binding used for $this pseudo-variable when evaluating closures.
     *
     * Returns $this.
     */
    public function set_binding($binding)
    {
        $this->binding = $binding;

        return $this;        
    }

    /**
     * Defines the formatters for ValidationErrors using a DSL.
     *
     * @param closure $definition_closure A closure using the DSL to define the formatting of
     *      the ValidationsError messages.
     * @param object $bindig An object that will be bound to the '$this' variable when evaluating
     *      each message formatter.
     *
     * @return Validation_Errors_Dictionary Returns $this instance.
     */
    public function define($definition_closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this->binding;
        }

        $this->_with_binding_do( $binding, $definition_closure );

        return $this;
    }

    /**
     * Evaluates the $definition_closure defined in the $file_path.
     *
     * @param string|File_Path $file_path The file with the definition closure.
     *
     * @return Assets_Library Returns $this object.
     */
    public function define_in_file($file_path)
    {
        if( is_string( $file_path ) ) {
            $file_path = Create::a( File_Path::class )->with( $file_path );
        }

        return $this->define( function($messages) use($file_path) {
            require( $file_path->to_string() );
        });
    }

    /**
     * Temporary sets $this->binding to a given binding, evaluates a closure and restores the original binding.
     *
     * @param object $bindig An object that will be bound to the '$this' variable when evaluating each message formatter.
     * @param closure $closure A closure.
     *
     * @return Validation_Errors_Dictionary Returns $this instance.
     */
    protected function _with_binding_do($binding, $closure)
    {
        $previous_binding = $this->binding;

        $this->binding = $binding;

        try{
            $closure->call( $this, $this );
        } finally {
            $this->binding = $previous_binding;
        }

        return $this;
    }

    /**
     * Defines a Closure_Context to format a Validation_Error for validations of type $validation_name.
     *
     * @param string $validation_name The name of the validations which messages will be formatted using the $formatter_closure.
     * @param closure $formatter_closure A closure that returns the formatted message.
     *
     * @return Validation_Errors_Dictionary Returns $this instance.
     */
    public function at_validation($validation_name, $formatter_closure)
    {
        $this->message_formatters_map[ $validation_name ] = $this->_new_closure_context( $formatter_closure );

        return $this;
    }

    /**
     * Drops the Closure_Context that formats a Validation_Error for validations of type $validation_name.
     *
     * @param string $validation_name The name of the validations to drop its formatter closure.
     *
     * @return Validation_Errors_Dictionary Returns $this instance.
     */
    public function drop_validation($validation_name)
    {
        unset( $this->message_formatters_map[ $validation_name ] );

        return $this;
    }

    /**
     * Defines a Closure_Context to format Validation_Error by default, unless a more specific closure is found
     * for a Validation_Error.
     *
     * @param closure $formatter_closure A closure that returns the formatted message.
     *
     * @return Validation_Errors_Dictionary Returns $this instance.
     */
    public function default($formatter_closure)
    {
        $this->default_message_formatter = $this->_new_closure_context( $formatter_closure );

        return $this;
    }

    /**
     * Drops the Closure_Context that formats a Validation_Error by default.
     *
     * @return Validation_Errors_Dictionary Returns $this instance.
     */
    public function drop_default()
    {
        $this->default_message_formatter = null;

        return $this;
    }

    /**
     * Creates and returns a new Closure_Context with a closure and the current $this->binding.
     *
     * @param closure $formatter_closure A closure that returns a formatted message.
     *
     * @return Closure_Context Returns the created Closure_Context.
     */
    protected function _new_closure_context($closure)
    {
        return Create::a( Closure_Context::class )->with( $this->binding, $closure );
    }

    /// Asking

    /**
     * Returns true if $this dictionary has a default Closure_Context defined, false if not.
     *
     * @return bool Returns true if this instance has a default Closure_Context defined, false if not.
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
     * Returns the defined Closure_Context for a $validation_name.
     *
     * @param string $validation_name The name of the validation to look up for its Closure_Context.
     *
     * @return Closure_Context The Closure_Context defined for the $validation_name.
     */
    protected function _closure_at($validation_name)
    {
        return $this->message_formatters_map[ $validation_name ];
    }

    /**
     * Returns a Closure_Context to format an error message for a Validation_Error.
     *
     * If $this dictionary has a Closure_Context for the Validation_Error::validation_name that Closure_Context
     * is returned. If not the default Closure_Context is returned.
     *
     * @param Validation_Error $validation_error The Validation_Error to look up for its formatter Closure_Context.
     *
     * @return Closure_Context The Closure_Context found for the Validation_Error.
     */
    protected function _closure_for($validation_error)
    {
        $validation_name = $validation_error->get_validation_name();

        if( $this->has_message_at( $validation_name ) ) {
            return $this->_closure_at( $validation_name );
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

        return $this->_closure_for( $validation_error )->evaluate( $validation_error );
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

        $this->_raise_message_not_found_error( $validation_error );
    }

    /**
     * Raises a Validation_Message_Not_Found_Exception.
     *
     * @param Validation_Error $validation_error The Validation_Error that is missing a formatter Closure_Context.
     */
    protected function _raise_message_not_found_error($validation_error)
    {
        throw Create::a( Validation_Message_Not_Found_Exception::class )
                ->with( $validation_error );
    }
}

// Initialize the Validation_Errors_Dictionary default initializer
Validation_Errors_Dictionary::set_dictionary_initializer(
    Create::a( Default_Dictionary_Initializer::class )->with()
);
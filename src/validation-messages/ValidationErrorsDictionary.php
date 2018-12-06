<?php

namespace Haijin\Validations;

/**
 * Provides a nice DSL to define error messages for ValidationErrors.
 *
 * Example
 *
 *      /// Create a new ValidationErrorsDictionary
 *
 *      // Use a dictionary with default messages
 *
 *      $errors_dictionary = ValidationErrorsDictionary::new_default();
 *
 *      // or create a new one
 *
 *      $errors_dictionary = new ValidationErrorsDictionary();
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
class ValidationErrorsDictionary
{
    /// Class methods

        /// Creating instances

    /**
     * An object to initialize default instances with validation message formatters.
     */
    static protected $dictionary_initializer = null;

    /**
     * Returns a new instance of a ValidationErrorsDictionary initalized with default message formatters.
     */
    static public function new_default()
    {
        return self::$dictionary_initializer->initialize( new self() );
    }

        /// Accessing

    /**
     * Returns the global dictionary initializer used to initialize instances of ValidationErrorsDictionary when
     * calling ValidationErrorsDictionary::default.
     */
    static public function get_dictionary_initializer()
    {
        return self::$dictionary_initializer;
    }

    /**
     * Sets the global dictionary initializer used to initialize instances of ValidationErrorsDictionary when
     * calling ValidationErrorsDictionary::default.
     */
    static public function set_dictionary_initializer($dictionary_initializer)
    {
        self::$dictionary_initializer = $dictionary_initializer;
    }

    /// Instance methods

    /**
     * A map from validation names to ClosureContexts that format ValidationErrors.
     */
    protected $message_formatters_map;

    /**
     * An optional ClosureContext acting as a default formatter when no other formatter is found for a ValidationError.
     */
    protected $default_message_formatter;

    /**
     * The binding to '$this' pseudo-variable when evaluating each formatter ClosureContext.
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
     * @return ValidationErrorsDictionary Returns $this instance.
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
     * @param string|FilePath $file_path The file with the definition closure.
     *
     * @return AssetsLibrary Returns $this object.
     */
    public function define_in_file($file_path)
    {
        if( is_string( $file_path ) ) {
            $file_path = new \Haijin\Tools\FilePath( $file_path );
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
     * @return ValidationErrorsDictionary Returns $this instance.
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
     * Defines a ClosureContext to format a ValidationError for validations of type $validation_name.
     *
     * @param string $validation_name The name of the validations which messages will be formatted using the $formatter_closure.
     * @param closure $formatter_closure A closure that returns the formatted message.
     *
     * @return ValidationErrorsDictionary Returns $this instance.
     */
    public function at_validation($validation_name, $formatter_closure)
    {
        $this->message_formatters_map[ $validation_name ] = $this->_new_closure_context( $formatter_closure );

        return $this;
    }

    /**
     * Drops the ClosureContext that formats a ValidationError for validations of type $validation_name.
     *
     * @param string $validation_name The name of the validations to drop its formatter closure.
     *
     * @return ValidationErrorsDictionary Returns $this instance.
     */
    public function drop_validation($validation_name)
    {
        unset( $this->message_formatters_map[ $validation_name ] );

        return $this;
    }

    /**
     * Defines a ClosureContext to format ValidationError by default, unless a more specific closure is found
     * for a ValidationError.
     *
     * @param closure $formatter_closure A closure that returns the formatted message.
     *
     * @return ValidationErrorsDictionary Returns $this instance.
     */
    public function default($formatter_closure)
    {
        $this->default_message_formatter = $this->_new_closure_context( $formatter_closure );

        return $this;
    }

    /**
     * Drops the ClosureContext that formats a ValidationError by default.
     *
     * @return ValidationErrorsDictionary Returns $this instance.
     */
    public function drop_default()
    {
        $this->default_message_formatter = null;

        return $this;
    }

    /**
     * Creates and returns a new ClosureContext with a closure and the current $this->binding.
     *
     * @param closure $formatter_closure A closure that returns a formatted message.
     *
     * @return ClosureContext Returns the created ClosureContext.
     */
    protected function _new_closure_context($closure)
    {
        return new \Haijin\Tools\ClosureContext( $this->binding, $closure );
    }

    /// Asking

    /**
     * Returns true if $this dictionary has a default ClosureContext defined, false if not.
     *
     * @return bool Returns true if this instance has a default ClosureContext defined, false if not.
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
     * Returns true if $this dictionary has a message formatter for a ValidationError, false if not.
     *
     * @param ValidationError $validation_name The ValidationError.
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
     * Returns the defined ClosureContext for a $validation_name.
     *
     * @param string $validation_name The name of the validation to look up for its ClosureContext.
     *
     * @return ClosureContext The ClosureContext defined for the $validation_name.
     */
    protected function _closure_at($validation_name)
    {
        return $this->message_formatters_map[ $validation_name ];
    }

    /**
     * Returns a ClosureContext to format an error message for a ValidationError.
     *
     * If $this dictionary has a ClosureContext for the ValidationError::validation_name that ClosureContext
     * is returned. If not the default ClosureContext is returned.
     *
     * @param ValidationError $validation_error The ValidationError to look up for its formatter ClosureContext.
     *
     * @return ClosureContext The ClosureContext found for the ValidationError.
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
     * Returns a formatted message string for a ValidationError.
     * If no formatter is found for the ValidationError a ValidationMessageNotFoundException is raised.
     *
     * @param ValidationError $validation_error The ValidationError to be formatted to a end user message string.
     *
     * @return string The message string to the end user for the ValidationError.
     */
    public function message_for($validation_error)
    {
        $this->validate_message_presence_for( $validation_error );

        return $this->_closure_for( $validation_error )->evaluate( $validation_error );
    }

    /// Errors

    /**
     * Validates that a message exists for a given ValidationError.
     * Raises a ValidationMessageNotFoundException if not.
     *
     * @param ValidationError $validation_error The ValidationError to validate for message presence.
     */
    public function validate_message_presence_for($validation_error)
    {
        if( $this->has_message_for( $validation_error ) )
            return;

        $this->_raise_message_not_found_error( $validation_error );
    }

    /**
     * Raises a ValidationMessageNotFoundException.
     *
     * @param ValidationError $validation_error The ValidationError that is missing a formatter ClosureContext.
     */
    protected function _raise_message_not_found_error($validation_error)
    {
        throw new ValidationMessageNotFoundException( $validation_error );
    }
}

// Initialize the ValidationErrorsDictionary default initializer
ValidationErrorsDictionary::set_dictionary_initializer( new DefaultDictionaryInitializer() );
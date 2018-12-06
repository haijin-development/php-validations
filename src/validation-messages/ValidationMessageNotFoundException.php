<?php

namespace Haijin\Validations;

/**
 * ValidationsException raised when trying to format a validation message for a missing valition name.
 */
class ValidationMessageNotFoundException extends ValidationsException
{
    /**
     * The ValidationError whose message formatter was missing.
     */
    protected $validation_error;

    /**
     * Initializes this instance with the ValidationError whose message formatter was missing.
     *
     * @param ValidationError $validation_error The ValidationError whose message formatter was missing.
     * @param string $error_message Optional - The text message for this Exception. If none is provided
     *          a default one is used.
     */
    public function __construct($validation_error, $error_message = null)
    {
        $this->validation_error = $validation_error;

        if( $error_message === null )
            $error_message = $this->default_error_message();

        parent::__construct( $error_message );
    }

    /**
     * Returns a default error message for this Exception.
     */
    protected function default_error_message()
    {
        return "No message formatter was found for the ValidationError \"{$this->validation_error->get_validation_name()}\"";
    }

    /// Accessing

    /**
     * Returns the ValidationError whose message formatter was missing.
     */
    public function get_validation_error()
    {
        return $this->validation_error;
    }
}
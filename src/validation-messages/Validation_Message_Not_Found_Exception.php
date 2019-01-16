<?php

namespace Haijin\Validations;

/**
 * Validations_Exception raised when trying to format a validation message for a missing valition name.
 */
class Validation_Message_Not_Found_Exception extends Validations_Exception
{
    /**
     * The Validation_Error whose message formatter was missing.
     */
    protected $validation_error;

    /**
     * Initializes this instance with the Validation_Error whose message formatter was missing.
     *
     * @param Validation_Error $validation_error The Validation_Error whose message formatter was missing.
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
        return "No message formatter was found for the Validation_Error \"{$this->validation_error->get_validation_name()}\"";
    }

    /// Accessing

    /**
     * Returns the Validation_Error whose message formatter was missing.
     */
    public function get_validation_error()
    {
        return $this->validation_error;
    }
}
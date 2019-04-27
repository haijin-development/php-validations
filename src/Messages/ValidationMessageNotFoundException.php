<?php

namespace Haijin\Validations\Messages;

use Haijin\Validations\ValidationsException;


/**
 * Validations_Exception raised when trying to format a validation message for a missing valition name.
 */
class ValidationMessageNotFoundException extends ValidationsException
{
    /**
     * The ValidationError whose message formatter was missing.
     */
    protected $validationError;

    /**
     * Initializes this instance with the ValidationError whose message formatter was missing.
     *
     * @param ValidationError $validationError The ValidationError whose message formatter was missing.
     * @param string $errorMessage Optional - The text message for this Exception. If none is provided
     *          a default one is used.
     */
    public function __construct($validationError, $errorMessage = null)
    {
        $this->validationError = $validationError;

        if ($errorMessage === null)
            $errorMessage = $this->defaultErrorMessage();

        parent::__construct($errorMessage);
    }

    /**
     * Returns a default error message for this Exception.
     */
    protected function defaultErrorMessage()
    {
        return "No message formatter was found for the ValidationError \"{$this->validationError->getValidationName()}\"";
    }

    /// Accessing

    /**
     * Returns the ValidationError whose message formatter was missing.
     */
    public function getValidationError()
    {
        return $this->validationError;
    }
}
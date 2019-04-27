<?php

namespace Haijin\Validations;


/**
 * An object holding the information of why a validation on an object failed.
 *
 * It holds the validated value, the attribute path to the value, the validation name and the validation
 * parameters, if any.
 *
 * It does not include any validation message because formatting messages to users should be done by the
 * application, not this library. The application or another library should format the appropiate message
 * to the end user from the information in this object.
 */
class ValidationError
{
    /**
     * The validated value.
     */
    protected $value;

    /**
     * The path from the root object to the validated attribute.
     */
    protected $attributePath;

    /**
     * The name of the validation that failed.
     */
    protected $validationName;

    /**
     * An array with the validation parameters, if any.
     */
    protected $validationParameters;

    /// Initializing

    /**
     * Initializes the ValidationError object.
     *
     * @param object $value The validated value.
     * @param AttributePath $attributePath The path from the root object to the validated nested attribute.
     * @param string $validationName The name of the validation that failed.
     * @param array $validationParameters An array with the validation parameters, if any.
     */
    public function __construct($value, $attributePath, $validationName, $validationParameters = [])
    {
        $this->value = $value;
        $this->attributePath = $attributePath;
        $this->validationName = $validationName;
        $this->validationParameters = $validationParameters;
    }

    /// Accessing

    /**
     * Returns the validated value.
     *
     * @return object The validated value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the path from the root object to the validated nested attribute.
     *
     * @return object The path from the root object to the validated nested attribute.
     */
    public function getAttributePath()
    {
        return $this->attributePath;
    }

    /**
     * Returns the name of the validation that failed.
     *
     * @return object The name of the validation that failed.
     */
    public function getValidationName()
    {
        return $this->validationName;
    }

    /**
     * Returns The array with the validation parameters, if any.
     *
     * @return object An array with the validation parameters, if any.
     */
    public function getValidationParameters()
    {
        return $this->validationParameters;
    }

    /**
     * Returns the name of the attribute, the last part in the attributes chain.
     *
     * @return string The name of the attribute.
     */
    public function getAttributeName()
    {
        return $this->attributePath->getLastAttribute();
    }
}
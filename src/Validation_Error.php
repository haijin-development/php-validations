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
class Validation_Error
{
    /**
     * The validated value.
     */
    protected $value;

    /**
     * The path from the root object to the validated attribute.
     */
    protected $attribute_path;

    /**
     * The name of the validation that failed.
     */
    protected $validation_name;

    /**
     * An array with the validation parameters, if any.
     */
    protected $validation_parameters;

    /// Initializing

    /**
     * Initializes the Validation_Error object.
     *
     * @param object $value The validated value.
     * @param Attribute_Path $attribute_path The path from the root object to the validated nested attribute.
     * @param string $validation_name The name of the validation that failed.
     * @param array $validation_parameters An array with the validation parameters, if any.
     */
    public function __construct($value, $attribute_path, $validation_name, $validation_parameters = [])
    {
        $this->value = $value;
        $this->attribute_path = $attribute_path;
        $this->validation_name = $validation_name;
        $this->validation_parameters = $validation_parameters;
    }

    /// Accessing

    /**
     * Returns the validated value.
     *
     * @return object The validated value.
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * Returns the path from the root object to the validated nested attribute.
     *
     * @return object The path from the root object to the validated nested attribute.
     */
    public function get_attribute_path()
    {
        return $this->attribute_path;
    }

    /**
     * Returns the name of the validation that failed.
     *
     * @return object The name of the validation that failed.
     */
    public function get_validation_name()
    {
        return $this->validation_name;
    }

    /**
     * Returns The array with the validation parameters, if any.
     *
     * @return object An array with the validation parameters, if any.
     */
    public function get_validation_parameters()
    {
        return $this->validation_parameters;
    }

    /**
     * Returns the name of the attribute, the last part in the attributes chain.
     *
     * @return string The name of the attribute.
     */
    public function get_attribute_name()
    {
        return $this->attribute_path->get_last_attribute();
    }
}
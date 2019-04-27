<?php

namespace Haijin\Validations;

/**
 * This trait has the definitions of the built in converters for the Validator class.
 */
trait BuiltInConverters
{
    /**
     * Converts the validated value to a string.
     *
     * @return Validator $this object
     */
    public function asString()
    {
        $this->setValidationName('asString');

        $this->setValue((string)$this->getValue());
    }

    /**
     * Converts the validated value to an integer.
     *
     * @return Validator $this object
     */
    public function asInt()
    {
        $this->setValidationName('asInt');

        $this->setValue((int)$this->getValue());

        return $this;
    }

    /**
     * Converts the validated value to a float.
     *
     * @return Validator $this object
     */
    public function asFloat()
    {
        $this->setValidationName('asFloat');

        $this->setValue((float)$this->getValue());

        return $this;
    }

    /**
     * Converts the validated value to a boolean.
     *
     * @return Validator $this object
     */
    public function asBoolean()
    {
        $this->setValidationName('asBoolean');

        $this->setValue((bool)$this->getValue());

        return $this;
    }
}
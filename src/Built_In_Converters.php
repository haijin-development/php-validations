<?php

namespace Haijin\Validations;

/**
 * This trait has the definitions of the built in converters for the Validator class.
 */
trait Built_In_Converters
{
    /**
     * Converts the validated value to a string.
     *
     * @return Validator $this object
     */
    public function as_string()
    {
        $this->set_validation_name( 'as_string' );

        $this->set_value( (string) $this->get_value() );
    }

    /**
     * Converts the validated value to an integer.
     *
     * @return Validator $this object
     */
    public function as_int()
    {
        $this->set_validation_name( 'as_int' );

        $this->set_value( (int) $this->get_value() );

        return $this;
    }

    /**
     * Converts the validated value to a float.
     *
     * @return Validator $this object
     */
    public function as_float()
    {
        $this->set_validation_name( 'as_float' );

        $this->set_value( (float) $this->get_value() );

        return $this;
    }

    /**
     * Converts the validated value to a boolean.
     *
     * @return Validator $this object
     */
    public function as_boolean()
    {
        $this->set_validation_name( 'as_boolean' );

        $this->set_value( (bool) $this->get_value() );

        return $this;
    }
}
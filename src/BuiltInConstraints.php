<?php

namespace Haijin\Validations;

/**
 * This trait has the definitions of the built in validations for the Validator class.
 */
trait BuiltInConstraints
{
    /// Presence constraints

    /**
     * Validates that the value is not null.
     *
     * @return Validator $this object
     */
    public function is_present()
    {
        $this->set_validation_name( 'is_present' );

        if( $this->get_value() === null ) {
            $this->add_error();
            $this->halt();            
        }

        return $this;
    }

    /**
     * Validates that the value is null.
     *
     * @return Validator $this object
     */
    public function not_present()
    {
        $this->set_validation_name( 'not_present' );

        if( $this->get_value() !== null ) {
            $this->add_error();
        }

        return $this;
    }

    /**
     * Validates that the value is not null.
     *
     * @return Validator $this object
     */
    public function is_optional()
    {
        $this->set_validation_name( 'is_optional' );

        if( $this->get_value() === null ) {
            $this->halt();
        }

        return $this;
    }

    /**
     * Validates that the value is empty.
     *
     * @return Validator $this object
     */
    public function is_empty()
    {
        $this->set_validation_name( 'is_empty' );

        if( count( $this->get_value() ) > 0 ) {
            $this->add_error();
        }

        return $this;
    }

    /**
     * Validates that the value is not empty.
     *
     * @return Validator $this object
     */
    public function not_empty()
    {
        $this->set_validation_name( 'not_empty' );

        if( count( $this->get_value() ) <= 0 ) {
            $this->add_error();
        }

        return $this;
    }

    /**
     * Validates that the value is not null and is not blank.
     *
     * @return Validator $this object
     */
    public function is_defined()
    {
        $this->set_validation_name( 'is_defined' );

        $validator = new self();

        $errors = $validator->validate( $this->get_value(), function($obj) {
            $obj ->is_present() ->not_blank();
        });

        if( count( $errors) == 0 ) {
            return $this;
        }

        $this->add_error();
        $this->halt();
    }

    /// String constraints

    /**
     * Validates that the string value is an blank string.
     * A blank string is a string that when trimmed equals ''.
     *
     * @return Validator $this object
     */
    public function is_blank()
    {
        $this->set_validation_name( 'is_blank' );

        $value = $this->get_value();

        if( is_string( $value ) && trim( $value ) != '' ) {
            $this->add_error();
        }

        if( is_array( $value ) && count( $value ) > 0 ) {
            $this->add_error();
        }

        return $this;
    }

    /**
     * Validates that the string value is not a blank string.
     * A blank string is a string that when trimmed equals ''.
     *
     * @return Validator $this object
     */
    public function not_blank()
    {
        $this->set_validation_name( 'not_blank' );

        $value = $this->get_value();

        if( is_string( $value ) && trim( $value ) == '' ) {
            $this->add_error();
        }

        if( is_array( $value ) && count( $value ) == 0 ) {
            $this->add_error();
        }

        return $this;
    }

    /**
     * Validates that the string value matches a regular expression.
     *
     * @return Validator $this object
     */
    public function matches($regex)
    {
        $this->set_validation_name( 'matches' );

        $value = $this->get_value();

        if( ! preg_match($regex, $this->get_value() ) ){
            $this->set_validation_parameters( [ $regex ] );
            $this->add_error();
        }

        return $this;
    }

    /// Type constraints

    /**
     * Validates that the value is a string.
     *
     * @return Validator $this object
     */
    public function is_string()
    {
        $this->set_validation_name( 'is_string' );

        if( ! is_string( $this->get_value() ) ) {
            $this->add_error();
            $this->halt();
        }

        return $this;
    }

    /**
     * Validates that the value is an integer.
     *
     * @return Validator $this object
     */
    public function is_int()
    {
        $this->set_validation_name( 'is_int' );

        if( ! is_int( $this->get_value() ) ) {
            $this->add_error();
            $this->halt();
        }

        return $this;
    }

    /**
     * Validates that the value is a dictionary.
     *
     * @return Validator $this object
     */
    public function is_float()
    {
        $this->set_validation_name( 'is_float' );

        if( ! is_float( $this->get_value() ) ) {
            $this->add_error();
            $this->halt();
        }

        return $this;
    }

    /**
     * Validates that the value is a number.
     *
     * @return Validator $this object
     */
    public function is_number()
    {
        $this->set_validation_name( 'is_number' );

        if( ! is_numeric( $this->get_value() ) || is_string( $this->get_value() ) )  {
            $this->add_error();
            $this->halt();
        }

        return $this;
    }

    /**
     * Validates that the value is a boolean.
     *
     * @return Validator $this object
     */
    public function is_boolean()
    {
        $this->set_validation_name( 'is_boolean' );

        if( ! is_bool( $this->get_value() ) ) {
            $this->add_error();
            $this->halt();
        }

        return $this;
    }

    /**
     * Validates that the value is an array.
     *
     * @return Validator $this object
     */
    public function is_array()
    {
        $this->set_validation_name( 'is_array' );

        if( ! is_array( $this->get_value() ) ) {
            $this->add_error();
            $this->halt();
        }

        return $this;
    }

    /**
     * Validates that the value is an object.
     *
     * @return Validator $this object
     */
    public function is_object()
    {
        $this->set_validation_name( 'is_object' );

        if( ! is_object( $this->get_value() ) ) {
            $this->add_error();
            $this->halt();
        }

        return $this;
    }

    /// Comparison

    /**
     * Validates that the value is an object.
     *
     * @return Validator $this object
     */
    public function is( $comparison_string, $another_value, $precision = null )
    {
        $this->set_validation_name( $comparison_string );
        $this->set_validation_parameters( [ $another_value ] );

        switch( $comparison_string ) {
            case '=':
                $this->set_validation_name( '==' );
            case '==':
                if( $this->get_value() == $another_value )
                    return $this;
                break;
            
            case '!=':
                if( $this->get_value() != $another_value )
                    return $this;
                break;

            case '<':
                if( $this->get_value() < $another_value )
                    return $this;
                break;

            case '<=':
                if( $this->get_value() <= $another_value )
                    return $this;
                break;

            case '>':
                if( $this->get_value() > $another_value )
                    return $this;
                break;

            case '>=':
                if( $this->get_value() >= $another_value )
                    return $this;
                break;

            case '~':
                if( abs( $this->get_value() - $another_value ) <= $precision )
                    return $this;

                $this->set_validation_parameters( [ $another_value, $precision ] );
                break;

            case '!~':
                if( abs( $this->get_value() - $another_value ) > $precision )
                    return $this;

                $this->set_validation_parameters( [ $another_value, $precision ] );
                break;

            default:
                throw new \Exception( "Invalid comparison operator {$comparison_string} in validation. Valid operatos are [ '==', '!=', '>', '>=', '<', '<=', '~', '!~' ]" );
                break;
        }

        $this->add_error();

        return $this;
    }

    /**
     * Validates that the length of the value is in a range.
     *
     * @return Validator $this object
     */
    public function length( $min_length, $max_length )
    {
        $this->set_validation_name( 'length' );

        if( is_string( $this->get_value() ) ){
            $length = strlen( $this->get_value() );
        }

        if( is_array( $this->get_value() ) ){
            $length = count( $this->get_value() );
        }

        if(  $length < $min_length || $length >$max_length ) {
            $this->set_validation_parameters( [ $min_length, $max_length ] );
            $this->add_error();
        }

        return $this;
    }

    /// Inclusion

    /**
     * Validates that the value is in a collection.
     *
     * @return Validator $this object
     */
    public function is_in( $collection )
    {
        $this->set_validation_name( 'is_in' );

        if( array_search( $this->get_value(), $collection ) === false ) {
            $this->set_validation_parameters( [ $collection ] );
            $this->add_error();    
        }

        return $this;
    }

    /**
     * Validates that the value is not in a collection.
     *
     * @return Validator $this object
     */
    public function not_in( $collection )
    {
        $this->set_validation_name( 'not_in' );

        if( array_search( $this->get_value(), $collection ) !== false ) {
            $this->set_validation_parameters( [ $collection ] );
            $this->add_error();    
        }

        return $this;
    }

    /**
     * Validates that the value includes another value.
     *
     * @return Validator $this object
     */
    public function has( $another_value )
    {
        $this->set_validation_name( 'has' );

        if( ! array_search( $another_value, $this->get_value() ) ) {
            $this->set_validation_parameters( [ $another_value ] );
            $this->add_error();    
        }

        return $this;
    }

    /**
     * Validates that the value does not include another value.
     *
     * @return Validator $this object
     */
    public function has_not( $another_value )
    {
        $this->set_validation_name( 'has_not' );

        if( array_search( $another_value, $this->get_value() ) ) {
            $this->set_validation_parameters( [ $another_value ] );
            $this->add_error();
        }

        return $this;
    }

    /**
     * Validates that the value includes all of the values from another collection.
     *
     * @return Validator $this object
     */
    public function has_all( $another_value )
    {
        $this->set_validation_name( 'has_all' );

        if( ! empty( array_diff( $another_value, $this->get_value() ) ) ) {
            $this->set_validation_parameters( [ $another_value ] );
            $this->add_error();    
        }

        return $this;
    }

    /**
     * Validates that the value includes any of the values from another collection.
     *
     * @return Validator $this object
     */
    public function has_any( $another_value )
    {
        $this->set_validation_name( 'has_any' );

        if( array_diff( $another_value, $this->get_value() ) == $another_value ) {
            $this->set_validation_parameters( [ $another_value ] );
            $this->add_error();    
        }

        return $this;
    }

    /**
     * Validates that the value includes none of the values from another collection.
     *
     * @return Validator $this object
     */
    public function has_none( $another_value )
    {
        $this->set_validation_name( 'has_none' );

        if( array_diff( $another_value, $this->get_value() ) != $another_value ) {
            $this->set_validation_parameters( [ $another_value ] );
            $this->add_error();
        }

        return $this;
    }

    /// Custom validations

    /**
     * Validates the value with a Validator instance, Validator class or closure provided as an argument.
     *
     * The closure receives $this Validator as a parameter to perform the validation actions
     * such as accessing the validate value, adding errors, halting, etc.
     *
     * @param callable|Validator|string $custom_validation A closure, Validator instance or Validator class
     *          to perform the validation.
     * @param object $this_binding Optional - An object to bind the $this pseudo-variable
     *          within the closure. If none is provided $this Validator will be bound.
     *
     * @return Validator $this Validator.
     */
    public function validate_with($custom_validation, $this_binding = null)
    {
        if( is_string( $custom_validation ) ) {
            $custom_validation = new  $custom_validation();
        }

        if( is_a( $custom_validation, 'Haijin\Validations\Validator' ) ) {
            return $this->validate_with_validator( $custom_validation, $this_binding );
        }

        if( $custom_validation instanceof \Closure ) {
            return $this->validate_with_callable( $custom_validation, $this_binding);
        }
    }

    /**
     * Validates the value with a Validator instance passed as an argument.
     *
     * @param Validator $custom_validation A Validator instance to perform the validation.
     *
     * @return Validator $this Validator.
     */
    public function validate_with_validator($custom_validation)
    {
        $custom_validation->set_value( $this->get_value() );
        $custom_validation->set_attribute_path( $this->get_attribute_path() );

        $custom_validation->evaluate();

        $this->add_all_errors( $custom_validation->get_errors() );

        return $this;
    }

    /**
     * Validates the value with a closure provided as an argument.
     *
     * The closure receives $this Validator as a parameter to perform the validation actions
     * such as accessing the validate value, adding errors, halting, etc.
     *
     * @param callable $custom_validation_callable A closure to perform the validation.
     * @param object $this_binding Optional - An object to bind the $this pseudo-variable
     *          within the closure. If none is provided $this Validator will be bound.
     *
     * @return Validator $this Validator.
     */
    public function validate_with_callable($custom_validation_callable, $this_binding = null)
    {
        if( $this_binding === null ) $this_binding = $this;

        $custom_validation_callable->call( $this_binding, $this );

        return $this;
    }
}
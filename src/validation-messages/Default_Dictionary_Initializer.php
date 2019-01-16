<?php

namespace Haijin\Validations;

/**
 * Object to initialize Validation_Errors_Dictionary with default message formatters.
 */
class Default_Dictionary_Initializer
{
    /**
     * Initializes the Validation_Errors_Dictionary with default message formatters.
     * Returns the Validation_Errors_Dictionary.
     *
     * @param Validation_Errors_Dictionary $validation_erros_dictionary The dictionary to initalize with
     *      message formatters.
     *
     * @return Validation_Errors_Dictionary The initialized dictionary.
     */
    public function initialize($validation_erros_dictionary)
    {
        $validation_erros_dictionary->define( function($messages) {

            $messages->at_validation( 'matches', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must match '{$validation_error->get_validation_parameters()[0]}'.";
            });

            $messages->at_validation( '==', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must equal {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( '!=', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must not equal {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( '<', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must be < {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( '<=', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must be <= {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( '>', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must be > {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( '>=', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must be >= {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( '~', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must be ~ {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( '!~', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must be !~ {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( 'has', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must have the element {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( 'has_not', function($validation_error) {
                return "The attribute '{$validation_error->get_attribute_path()}' must not have the element {$validation_error->get_validation_parameters()[0]}.";
            });

            $messages->at_validation( 'has_all', function($validation_error) {
                $elements = join( ', ', $validation_error->get_validation_parameters()[0] );

                return "The attribute '{$validation_error->get_attribute_path()}' must have all the elements [{$elements}].";
            });

            $messages->at_validation( 'has_any', function($validation_error) {
                $elements = join( ', ', $validation_error->get_validation_parameters()[0] );

                return "The attribute '{$validation_error->get_attribute_path()}' must have any of the elements [{$elements}].";
            });

            $messages->at_validation( 'has_none', function($validation_error) {
                $elements = join( ', ', $validation_error->get_validation_parameters()[0] );

                return "The attribute '{$validation_error->get_attribute_path()}' must have none of the elements [{$elements}].";
            });

            $messages->at_validation( 'is_in', function($validation_error) {
                $elements = join( ', ', $validation_error->get_validation_parameters()[0] );

                return "The attribute '{$validation_error->get_attribute_path()}' must be one of [{$elements}].";
            });

            $messages->at_validation( 'not_in', function($validation_error) {
                $elements = join( ', ', $validation_error->get_validation_parameters()[0] );

                return "The attribute '{$validation_error->get_attribute_path()}' must be none of [{$elements}].";
            });

            $messages->at_validation( 'length', function($validation_error) {
                $params = $validation_error->get_validation_parameters();

                if( $params[0] !== null && $params[1] !== null ){
                    $length = "a length between {$params[0]} and {$params[1]}";
                } elseif( $params[0] !== null ) {
                    $length = "a minimum length of {$params[0]}";
                } elseif( $params[1] !== null ) {
                    $length = "a maximum length of {$params[1]}";
                }

                return "The attribute '{$validation_error->get_attribute_path()}' must have {$length}.";
            });

            $messages->default( function($validation_error) {

                $validation_name = $validation_error->get_validation_name();
                $attribute_path = $validation_error->get_attribute_path();


                if( $this->is_type_validation($validation_name) ) {
                    return $this->format_type_validation( $attribute_path, $validation_name );
                }

                if( $match = $this->extract_pattern_from( '/^is_(.+)$/', $validation_name ) ) {
                    return "The attribute '{$attribute_path}' must be {$match}.";
                }

                if( $match = $this->extract_pattern_from( '/^not_(.+)$/', $validation_name ) ) {
                    return "The attribute '{$attribute_path}' must not be {$match}.";
                }

                return "The attribute '{$attribute_path}' is not valid.";
            });

        }, $this);

        return $validation_erros_dictionary;
    }

    protected function extract_pattern_from( $pattern, $validation_name)
    {
        $matches = [];

        if( preg_match($pattern, $validation_name, $matches ) ) {
            return $matches[1];
        }

        return null;        
    }

    protected function is_type_validation($validation_name)
    {
        $type_validations = [ 'is_string', 'is_int', 'is_float', 'is_number', 'is_boolean', 'is_array', 'is_object' ];

        return in_array( $validation_name, $type_validations );
    }

    protected function format_type_validation($attribute_path, $validation_name)
    {
        $match = $this->extract_pattern_from( '/^is_(.+)$/', $validation_name );

        if( $this->is_vowel( $match[0] ) )
            $joiner = 'an';
        else
            $joiner = 'a';

        return "The attribute '{$attribute_path}' must be {$joiner} {$match}.";
    }

    protected function is_vowel($char)
    {
        return in_array( $char, [ 'a', 'e', 'i', 'o', 'u' ] );
    }
}
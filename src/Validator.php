<?php

namespace Haijin\Validations;

use Haijin\Ordered_Collection;
use Haijin\Attribute_Path;
use Haijin\Object_Attribute_Accessor;
use Haijin\Errors\Haijin_Error;
use Haijin\Validations\Halt_Validation_Exception;

class Validator implements \ArrayAccess
{
    use \Haijin\Validations\Built_In_Constraints;
    use \Haijin\Validations\Built_In_Converters;

    protected $value;
    protected $attribute_path;
    protected $validation_name;
    protected $validation_parameters;
    protected $errors_collection;

    /// Initializing

    public function __construct()
    {
        $this->value = null;
        $this->attribute_path = $this->_new_attribute_path();
        $this->validation_name = null;
        $this->validation_parameters = [];
        $this->errors_collection = new Ordered_Collection();
    }

    /// Callable protocol

    public function __invoke($validator)
    {
        $this->set_value( $validator->get_value() );
        $this->set_attribute_path( $validator->get_attribute_path() );
        $this->set_errors_collection( $validator->get_errors_collection() );

        $this->evaluate();
    }

    public function evaluate()
    {
        $this->raise_missing_evaluate_method_error();
    }

    // Accessing

    /**
     * Returns the value of the attribute being validated.
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * Sets the value of the attribute being validated.
     * Returns $this so setters can be chained.
     */
    public function set_value($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Returns the path of the attribute being validated.
     */
    public function get_attribute_path()
    {
        return $this->attribute_path;        
    }

    /**
     * Sets the attribute_path of the attribute being validated.
     */
    public function set_attribute_path($attribute_path)
    {
        $this->attribute_path = $this->_new_attribute_path( $attribute_path );

        return $this;
    }

    /**
     * Returns the collection of collected Validation_Errors.
     */
    public function get_errors()
    {
        return $this->errors_collection->to_array();
    }

    /**
     * Gets the errors_collection.
     * Returns $this.
     */
    public function get_errors_collection()
    {
        return $this->errors_collection;
    }

    /**
     * Sets the errors_collection.
     * Returns $this.
     */
    public function set_errors_collection($errors_collection)
    {
        $this->errors_collection = $errors_collection;

        return $this;
    }

    /**
     * Returns the validation_name.
     */
    public function get_validation_name()
    {
        return $this->validation_name;
    }

    /**
     * Sets the validation_name.
     * Returns $this.
     */
    public function set_validation_name($validation_name)
    {
        $this->validation_name = $validation_name;

        return $this;        
    }

    /**
     * Returns the validation_parameters.
     */
    public function get_validation_parameters()
    {
        return $this->validation_parameters;
    }

    /**
     * Sets the validation_parameters.
     * Returns $this.
     */
    public function set_validation_parameters($validation_parameters)
    {
        $this->validation_parameters = $validation_parameters;

        return $this;        
    }

    /// Accessing nested attributes


    /**
     * Returns the value of the attribute at $attribute_chain starting at the current $this->value.
     *
     * This method is usefull in validations that need to compare or validate other values than
     * $this->value. For instance, when validating that partial sums equals a total.
     *
     * @param string|array|Attribute_Path $attribute_chain The attribute path to the value.
     *
     * @return object The value read from $this->value following the attribute chain.
     */
    public function get_value_at($attribute_chain)
    {
        $value = $this->get_value();
        $accessor = new Object_Attribute_Accessor( $value );

        return $accessor->get_value_at_if_absent( $attribute_chain, null );
    }

    // Validation DSL

    /**
     * Evaluates a validation DSL callable collecting the validation errors.
     *
     * Returns an array with all the validation errors collected.
     */
    public function validate($value, $validation_callable)
    {
        $this->set_value( $value );

        $this->_isolate( $validation_callable );

        return $this->get_errors();
    }

    /**
     * Evaluates a validation DSL callable on $this Validator.
     *
     * Returns $this Validator.
     */
    public function eval($validation_callable)
    {
        $validation_callable( $this );

        return $this;
    }

    public function attr($attribute_name, $validation_callable = null)
    {
        $nested_value = $this->get_value_at( $attribute_name );

        $nested_validation = $this->new_validator();
        $nested_validation->set_value( $nested_value );
        $nested_validation->set_attribute_path(
            $this->get_attribute_path()->concat( $attribute_name )
        );
        $nested_validation->set_errors_collection( $this->errors_collection );

        if( $validation_callable !== null ) {
            $nested_validation->_isolate( $validation_callable );
        }

        $this->value = ( new Object_Attribute_Accessor( $this->value ) )
                ->set_value_at( $attribute_name, $nested_validation->get_value() );

        return $nested_validation;
    }

    public function each( $each_item_validation_callable )
    {
        foreach( $this->get_value() as $index => $item) {
            $attribute_name = "[" . $index . "]";

            $this->attr( $attribute_name, $each_item_validation_callable );
        }
    }

    /**
     * Evaluates a validation DSL callable on $this Validator catching
     * HaltValidationExceptions.
     *
     * Returns $this Validator.
     */
    protected function _isolate($validation_callable)
    {
        try
        {
            $this->eval( $validation_callable );

        } catch( Halt_Validation_Exception $e )
        {
        }

        return $this;
    }

    // Errors

    /**
     * Adds a all the Validation_Error in $validation_errors_array to the errors collection.
     */
    public function add_all_errors($validation_errors_array)
    {
        $this->errors_collection->add_all( $validation_errors_array );
    }

    /**
     * Creates a new Validation_Error and adds it to the errors collection.
     */
    public function add_error($params = [])
    {
        $this->add_validation_error( $this->new_validation_error($params) );
    }

    /**
     * Adds a Validation_Error to the errors collection.
     */
    public function add_validation_error($validation_error)
    {
        $this->errors_collection->add( $validation_error );
    }

    /**
     * Halts the validation on the current attribute branch.
     */
    public function halt()
    {
        throw new Halt_Validation_Exception();
    }

    /**
     * Creates and returns a new Validation_Error.
     * Does not add the Validation_Error to the errors collection.
     */
    public function new_validation_error($params = [])
    {
        if( ! isset( $params[ 'value' ] ) ) {
            $params['value'] = $this->get_value();
        }

        if( isset( $params[ 'attribute_path' ] ) ) {
            $params['attribute_path'] =
                $this->_new_attribute_path( $params['attribute_path'] );
        } else {
            $params['attribute_path'] = $this->get_attribute_path();
        }

        if( ! isset( $params[ 'validation_name' ] ) ) {
            $params['validation_name'] = $this->get_validation_name();
        }

        if( ! isset( $params[ 'validation_parameters' ] ) ) {
            $params['validation_parameters'] = $this->get_validation_parameters();
        }

        return new Validation_Error(
            $params['value'],
            $params['attribute_path'],
            $params['validation_name'],
            $params['validation_parameters'] 
        );
    }

    protected function new_validator()
    {
        $class_name = get_class( $this );

        return new $class_name();
    }

    /// Creating instances

    /**
     * Creates and returns a new instance of an Attribute_Path initialized with $attribute_path.
     *
     * @param string|array|Attribute_Path The initial path.
     *
     * @return Attribute_Path The Attribute_Path created.
     */
    protected function _new_attribute_path($attribute_path = [])
    {
        return new Attribute_Path( $attribute_path );
    }

    /// Raising errors

    protected function raise_missing_evaluate_method_error()
    {
        $subclass_name = get_class( $this );

        throw new Haijin_Error(
            "'{$subclass_name}' must implement a 'public function evaluate()' with the validations for the object being validated."
        );        
    }


    /// ArrayAccess implementation

    public function offsetExists( $offset )
    {
        return true;
    }

    public function offsetGet( $attribute_name )
    {
        return $this->attr( $attribute_name );
    }

    public function offsetSet( $offset , $value )
    {
        throw new Haijin_Error(
            "Attribute assignment through [] is not supported."
        );
    }

    public function offsetUnset( $offset )
    {
        throw new Haijin_Error(
            "Attribute unset() is not supported."
        );
    }
}
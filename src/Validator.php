<?php

namespace Haijin\Validations;

use Haijin\Validations\HaltValidationException;
use Haijin\Tools\ObjectAttributeAccessor;

class Validator implements \ArrayAccess
{
    use \Haijin\Validations\BuiltInConstraints;
    use \Haijin\Validations\BuiltInConverters;

    protected $value;
    protected $attribute_path;
    protected $validation_name;
    protected $validation_parameters;
    protected $errors_collection;
    protected $binding;

    public function __construct()
    {
        $this->value = null;
        $this->attribute_path = $this->_new_attribute_path();
        $this->validation_name = null;
        $this->validation_parameters = [];
        $this->errors_collection = new \Haijin\Tools\OrderedCollection();
        $this->binding = $this;
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
     * Returns the collection of collected ValidationErrors.
     */
    public function get_errors()
    {
        return $this->errors_collection->to_array();
    }

    /**
     * Gets the errors_collection.
     * Returns $this.
     */
    public function get_errors_collection($errors_collection)
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

    /**
     * Sets the binding used for $this pseudo-variable when evaluating closures.
     *
     * Returns $this.
     */
    public function set_binding($binding)
    {
        $this->binding = $binding;

        return $this;
    }

    /// Accessing nested attributes


    /**
     * Returns the value of the attribute at $attribute_chain starting at the current $this->value.
     *
     * This method is usefull in validations that need to compare or validate other values than
     * $this->value. For instance, when validating that partial sums equals a total.
     *
     * @param string|array|AttributePath $attribute_chain The attribute path to the value.
     *
     * @return object The value read from $this->value following the attribute chain.
     */
    public function get_value_at($attribute_chain)
    {
        $value = $this->get_value();
        $accessor = new ObjectAttributeAccessor( $value );

        if( $accessor->not_defined( $attribute_chain ) )
            return null;

        return $accessor->get_value_at( $attribute_chain );
    }

    // Validation DSL

    /**
     * Evaluates a validation DSL closure collecting the validation errors.
     *
     * Returns an array with all the validation errors collected.
     */
    public function validate($value, $validation_closure, $binding = null)
    {
        if( $binding == null ) {
            $binding = $this->binding;
        }

        $this->set_value( $value );

        $this->_with_binding_do( $binding, function() use($validation_closure) {
            $this->_isolate( $validation_closure );
        });

        return $this->get_errors();
    }

    /**
     * Evaluates a validation DSL closure on $this Validator.
     *
     * Returns $this Validator.
     */
    public function eval($validation_closure)
    {
        $validation_closure->call( $this->binding, $this );

        return $this;
    }

    public function attr($attribute_name, $validation_closure = null)
    {
        $nested_value = $this->get_value_at( $attribute_name );

        $nested_validation = $this->new_validator();
        $nested_validation->set_value( $nested_value );
        $nested_validation->set_attribute_path(
            $this->get_attribute_path()->concat( $attribute_name )
        );
        $nested_validation->set_binding( $this->binding );
        $nested_validation->set_errors_collection( $this->errors_collection );

        if( $validation_closure != null )
            $nested_validation->_isolate( $validation_closure );

        return $nested_validation;
    }

    public function each( $each_item_validation_closure )
    {
        foreach( $this->get_value() as $index => $item) {
            $attribute_name = "[" . $index . "]";

            $this->attr( $attribute_name, $each_item_validation_closure );
        }
    }

    /**
     * Temporary sets $this->binding to a given binding, evaluates a closure and restores the original binding.
     *
     * @param object $bindig An object that will be bound to the '$this' variable when evaluating each message formatter.
     * @param closure $closure A closure.
     *
     * @return ValidationErrorsDictionary Returns $this instance.
     */
    protected function _with_binding_do($binding, $closure)
    {
        $previous_binding = $this->binding;

        $this->binding = $binding;

        try{
            $closure->call( $this, $this );
        } finally {
            $this->binding = $previous_binding;
        }

        return $this;
    }

    /**
     * Evaluates a validation DSL closure on $this Validator catching HaltValidationExceptions.
     *
     * Returns $this Validator.
     */
    protected function _isolate($validation_closure)
    {
        try
        {
            $this->eval( $validation_closure );

        } catch( HaltValidationException $e )
        {
        }

        return $this;
    }

    // Errors

    /**
     * Adds a all the ValidationError in $validation_errors_array to the errors collection.
     */
    public function add_all_errors($validation_errors_array)
    {
        $this->errors_collection->add_all( $validation_errors_array );
    }

    /**
     * Creates a new ValidationError and adds it to the errors collection.
     */
    public function add_error($params = [])
    {
        $this->add_validation_error( $this->new_validation_error($params) );
    }

    /**
     * Adds a ValidationError to the errors collection.
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
        throw new HaltValidationException();
    }

    /**
     * Creates and returns a new ValidationError.
     * Does not add the ValidationError to the errors collection.
     */
    public function new_validation_error($params = [])
    {
        if( ! array_key_exists( 'value', $params ) )
            $params['value'] = $this->get_value();

        if( array_key_exists( 'attribute_path', $params ) )
            $params['attribute_path'] = $this->_new_attribute_path( $params['attribute_path'] );
        else
            $params['attribute_path'] = $this->get_attribute_path();

        if( ! array_key_exists( 'validation_name', $params ) )
            $params['validation_name'] = $this->get_validation_name();

        if( ! array_key_exists( 'validation_parameters', $params ) )
            $params['validation_parameters'] = $this->get_validation_parameters();

        return new ValidationError(
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
     * Creates and returns a new instance of an AttributePath initialized with $attribute_path.
     *
     * @param string|array|AttributePath The initial path.
     *
     * @return AttributePath The AttributePath created.
     */
    protected function _new_attribute_path($attribute_path = [])
    {
        return new \Haijin\Tools\AttributePath( $attribute_path );
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
        throw new \Exception( "Attribute assignment through [] is not supported." );
    }

    public function offsetUnset( $offset )
    {
        throw new \Exception( "Attribute unset() is not supported." );
    }
}
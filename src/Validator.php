<?php

namespace Haijin\Validations;

use Haijin\AttributePath;
use Haijin\ObjectAttributeAccessor;
use Haijin\OrderedCollection;

class Validator
{
    use \Haijin\Validations\BuiltInConstraints;
    use \Haijin\Validations\BuiltInConverters;

    protected $value;
    protected $attributePath;
    protected $validationName;
    protected $validationParameters;
    protected $errorsCollection;

    /// Initializing

    public function __construct()
    {
        $this->value = null;
        $this->attributePath = $this->_newAttributePath();
        $this->validationName = null;
        $this->validationParameters = [];
        $this->resetErrors();
    }

    /// Callable protocol

    public function __invoke($validator)
    {
        $this->setValue($validator->getValue());
        $this->setAttributePath($validator->getAttributePath());
        $this->setErrorsCollection($validator->getErrorsCollection());

        $this->evaluate();
    }

    public function resetErrors()
    {
        $this->errorsCollection = new OrderedCollection();
    }


    // Accessing

    /**
     * Returns the value of the attribute being validated.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the attribute being validated.
     * Returns $this so setters can be chained.
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Returns the path of the attribute being validated.
     */
    public function getAttributePath()
    {
        return $this->attributePath;
    }

    /**
     * Sets the attributePath of the attribute being validated.
     */
    public function setAttributePath($attributePath)
    {
        $this->attributePath = $this->_newAttributePath($attributePath);

        return $this;
    }

    /**
     * Returns the collection of collected ValidationErrors.
     */
    public function getErrors()
    {
        return $this->errorsCollection->toArray();
    }

    /**
     * Gets the errorsCollection.
     * Returns $this.
     */
    public function getErrorsCollection()
    {
        return $this->errorsCollection;
    }

    /**
     * Sets the errorsCollection.
     * Returns $this.
     */
    public function setErrorsCollection($errorsCollection)
    {
        $this->errorsCollection = $errorsCollection;

        return $this;
    }

    /**
     * Returns the validationName.
     */
    public function getValidationName()
    {
        return $this->validationName;
    }

    /**
     * Sets the validationName.
     * Returns $this.
     */
    public function setValidationName($validationName)
    {
        $this->validationName = $validationName;

        return $this;
    }

    /**
     * Returns the validationParameters.
     */
    public function getValidationParameters()
    {
        return $this->validationParameters;
    }

    /**
     * Sets the validationParameters.
     * Returns $this.
     */
    public function setValidationParameters($validationParameters)
    {
        $this->validationParameters = $validationParameters;

        return $this;
    }

    /// Accessing nested attributes


    /**
     * Returns the value of the attribute at $attributeChain starting at the current $this->value.
     *
     * This method is usefull in validations that need to compare or validate other values than
     * $this->value. For instance, when validating that partial sums equals a total.
     *
     * @param string|array|AttributePath $attributeChain The attribute path to the value.
     *
     * @return object The value read from $this->value following the attribute chain.
     */
    public function getValueAt($attributeChain)
    {
        $value = $this->getValue();
        $accessor = new ObjectAttributeAccessor($value);

        return $accessor->getValueAtIfAbsent($attributeChain, null);
    }

    // Validation DSL

    /**
     * Evaluates a validation DSL callable collecting the validation errors.
     *
     * Returns an array with all the validation errors collected.
     */
    public function validate($value, $validationCallable)
    {
        $this->resetErrors();

        $this->setValue($value);

        $this->_isolate($validationCallable);

        return $this->getErrors();
    }

    /**
     * Evaluates a validation DSL callable on $this Validator.
     *
     * Returns $this Validator.
     */
    public function eval($validationCallable)
    {
        $validationCallable($this);

        return $this;
    }

    public function attr($attributeName, $validationCallable = null)
    {
        $nestedValue = $this->getValueAt($attributeName);

        $nestedValidation = $this->newValidator();
        $nestedValidation->setValue($nestedValue);
        $nestedValidation->setAttributePath(
            $this->getAttributePath()->concat($attributeName)
        );
        $nestedValidation->setErrorsCollection($this->errorsCollection);

        if ($validationCallable !== null) {
            $nestedValidation->_isolate($validationCallable);
        }

        $this->value = (new ObjectAttributeAccessor($this->value))
            ->setValueAt($attributeName, $nestedValidation->getValue());

        return $nestedValidation;
    }

    public function each($eachItemValidationCallable)
    {
        foreach ($this->getValue() as $index => $item) {
            $attributeName = "[" . $index . "]";

            $this->attr($attributeName, $eachItemValidationCallable);
        }
    }

    /**
     * Evaluates a validation DSL callable on $this Validator catching
     * HaltValidationExceptions.
     *
     * Returns $this Validator.
     */
    protected function _isolate($validationCallable)
    {
        try {
            $this->eval($validationCallable);

        } catch (HaltValidationException $e) {
        }

        return $this;
    }

    // Errors

    /**
     * Creates a new ValidationError and adds it to the errors collection.
     */
    public function addError($params = [])
    {
        $this->addValidationError($this->newValidationError($params));
    }

    /**
     * Adds a ValidationError to the errors collection.
     */
    public function addValidationError($validationError)
    {
        $this->errorsCollection->add($validationError);
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
    public function newValidationError($params = [])
    {
        if (!isset($params['value'])) {
            $params['value'] = $this->getValue();
        }

        if (isset($params['attributePath'])) {
            $params['attributePath'] =
                $this->_newAttributePath($params['attributePath']);
        } else {
            $params['attributePath'] = $this->getAttributePath();
        }

        if (!isset($params['validationName'])) {
            $params['validationName'] = $this->getValidationName();
        }

        if (!isset($params['validationParameters'])) {
            $params['validationParameters'] = $this->getValidationParameters();
        }

        return new ValidationError(
            $params['value'],
            $params['attributePath'],
            $params['validationName'],
            $params['validationParameters']
        );
    }

    protected function newValidator()
    {
        $className = get_class($this);

        return new $className();
    }

    /// Creating instances

    /**
     * Creates and returns a new instance of an AttributePath initialized with $attributePath.
     *
     * @param string|array|AttributePath The initial path.
     *
     * @return AttributePath The AttributePath created.
     */
    protected function _newAttributePath($attributePath = [])
    {
        return new AttributePath($attributePath);
    }
}
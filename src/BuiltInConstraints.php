<?php

namespace Haijin\Validations;

use Haijin\Errors\HaijinError;

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
    public function isPresent()
    {
        $this->setValidationName('isPresent');

        if ($this->getValue() === null) {

            $this->addError();

            return $this->halt();
        }

        return $this;
    }

    /**
     * Validates that the value is null.
     *
     * @return Validator $this object
     */
    public function notPresent()
    {
        $this->setValidationName('notPresent');

        if ($this->getValue() !== null) {
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value is not null.
     * If an optional $defaultValue is given sets the value to the default value.
     * Otherwise halts further validations without failing.
     *
     * @return Validator $this object
     */
    public function isOptional($defaultValue = null)
    {
        $this->setValidationName('isOptional');

        if ($this->getValue() === null) {

            if ($defaultValue === null) {
                return $this->halt();
            }

            $this->setValue($defaultValue);
        }

        return $this;
    }

    /**
     * Validates that the value is empty.
     *
     * @return Validator $this object
     */
    public function isEmpty()
    {
        $this->setValidationName('isEmpty');

        if (count($this->getValue()) > 0) {
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value is not empty.
     *
     * @return Validator $this object
     */
    public function notEmpty()
    {
        $this->setValidationName('notEmpty');

        if (count($this->getValue()) <= 0) {
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value is not null and is not blank.
     *
     * @return Validator $this object
     */
    public function isDefined()
    {
        $this->setValidationName('isDefined');

        $validator = new self();

        $errors = $validator->validate($this->getValue(), function ($obj) {
            $obj->isPresent()->notBlank();
        });

        if (count($errors) == 0) {
            return $this;
        }

        $this->addError();

        return $this->halt();
    }

    /// String constraints

    /**
     * Validates that the string value is an blank string.
     * A blank string is a string that when trimmed equals ''.
     *
     * @return Validator $this object
     */
    public function isBlank()
    {
        $this->setValidationName('isBlank');

        $value = $this->getValue();

        if (is_string($value) && trim($value) != '') {
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the string value is not a blank string.
     * A blank string is a string that when trimmed equals ''.
     *
     * @return Validator $this object
     */
    public function notBlank()
    {
        $this->setValidationName('notBlank');

        $value = $this->getValue();

        if (is_string($value) && trim($value) == '') {
            $this->addError();
        }

        if (is_array($value) && count($value) == 0) {
            $this->addError();
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
        $this->setValidationName('matches');

        $value = $this->getValue();

        if (!preg_match($regex, $this->getValue())) {
            $this->setValidationParameters([$regex]);
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the string value is formatted as a valid email.
     *
     * @return Validator $this object
     */
    public function isEmail()
    {
        $this->setValidationName('isEmail');

        $value = $this->getValue();

        if (!(new \EmailValidator\Validator())->isEmail($value)) {
            $this->addError();
        }

        return $this;
    }

    /// Type constraints

    /**
     * Validates that the value is a string.
     *
     * @return Validator $this object
     */
    public function isString()
    {
        $this->setValidationName('isString');

        if (!is_string($this->getValue())) {

            $this->addError();

            return $this->halt();

        }

        return $this;
    }

    /**
     * Validates that the value is an integer.
     *
     * @return Validator $this object
     */
    public function isInt()
    {
        $this->setValidationName('isInt');

        if (!is_int($this->getValue())) {

            $this->addError();

            return $this->halt();

        }

        return $this;
    }

    /**
     * Validates that the value is a dictionary.
     *
     * @return Validator $this object
     */
    public function isFloat()
    {
        $this->setValidationName('isFloat');

        if (!is_float($this->getValue())) {

            $this->addError();

            return $this->halt();

        }

        return $this;
    }

    /**
     * Validates that the value is a number.
     *
     * @return Validator $this object
     */
    public function isNumber()
    {
        $this->setValidationName('isNumber');

        if (!is_numeric($this->getValue()) || is_string($this->getValue())) {

            $this->addError();

            return $this->halt();

        }

        return $this;
    }

    /**
     * Validates that the value is a boolean.
     *
     * @return Validator $this object
     */
    public function isBoolean()
    {
        $this->setValidationName('isBoolean');

        if (!is_bool($this->getValue())) {

            $this->addError();

            return $this->halt();

        }

        return $this;
    }

    /**
     * Validates that the value is an array.
     *
     * @return Validator $this object
     */
    public function isArray()
    {
        $this->setValidationName('isArray');

        if (!is_array($this->getValue())) {

            $this->addError();

            return $this->halt();

        }

        return $this;
    }

    /**
     * Validates that the value is an object.
     *
     * @return Validator $this object
     */
    public function isObject()
    {
        $this->setValidationName('isObject');

        if (!is_object($this->getValue())) {

            $this->addError();

            return $this->halt();

        }

        return $this;
    }

    /// Comparison

    /**
     * Validates that the value is an object.
     *
     * @return Validator $this object
     */
    public function is($comparisonString, $anotherValue, $precision = null)
    {
        $this->setValidationName($comparisonString);
        $this->setValidationParameters([$anotherValue]);

        switch ($comparisonString) {
            case '=':
                $this->setValidationName('==');
            case '==':
                if ($this->getValue() == $anotherValue)
                    return $this;
                break;

            case '!=':
                if ($this->getValue() != $anotherValue)
                    return $this;
                break;

            case '<':
                if ($this->getValue() < $anotherValue)
                    return $this;
                break;

            case '<=':
                if ($this->getValue() <= $anotherValue)
                    return $this;
                break;

            case '>':
                if ($this->getValue() > $anotherValue)
                    return $this;
                break;

            case '>=':
                if ($this->getValue() >= $anotherValue)
                    return $this;
                break;

            case '~':
                if (abs($this->getValue() - $anotherValue) <= $precision)
                    return $this;

                $this->setValidationParameters([$anotherValue, $precision]);
                break;

            case '!~':
                if (abs($this->getValue() - $anotherValue) > $precision)
                    return $this;

                $this->setValidationParameters([$anotherValue, $precision]);
                break;

            default:
                throw new HaijinError("Invalid comparison operator '{$comparisonString}' in validation. Valid operatos are [ '==', '!=', '>', '>=', '<', '<=', '~', '!~' ]");
                break;
        }

        $this->addError();

        return $this;
    }

    /**
     * Validates that the length of the value is in a range.
     *
     * @return Validator $this object
     */
    public function length($minLength, $maxLength)
    {
        $this->setValidationName('length');

        if (is_string($this->getValue())) {
            $length = strlen($this->getValue());
        }

        if (is_array($this->getValue())) {
            $length = count($this->getValue());
        }

        if ($length < $minLength || $length > $maxLength) {
            $this->setValidationParameters([$minLength, $maxLength]);
            $this->addError();
        }

        return $this;
    }

    /// Validations

    public function sameValueAt($attribute, $anotherAttribute)
    {
        $this->setValidationName('sameValueAt');

        if ($this->getValueAt($attribute)
            !=
            $this->getValueAt($anotherAttribute)
        ) {
            $this->setValidationParameters([$attribute, $anotherAttribute]);
            $this->addError([
                'value' => $this->getValueAt($attribute),
                'attributePath' => $this->getAttributePath()->concat($attribute),
                'validationParameters' => [
                    $anotherAttribute,
                    $this->getValueAt($anotherAttribute)
                ]
            ]);
        }

        return $this;
    }

    /// Inclusion

    /**
     * Validates that the value is in a collection.
     *
     * @return Validator $this object
     */
    public function isIn($collection)
    {
        $this->setValidationName('isIn');

        if (array_search($this->getValue(), $collection) === false) {
            $this->setValidationParameters([$collection]);
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value is not in a collection.
     *
     * @return Validator $this object
     */
    public function notIn($collection)
    {
        $this->setValidationName('notIn');

        if (array_search($this->getValue(), $collection) !== false) {
            $this->setValidationParameters([$collection]);
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value includes another value.
     *
     * @return Validator $this object
     */
    public function has($anotherValue)
    {
        $this->setValidationName('has');

        if (!array_search($anotherValue, $this->getValue())) {
            $this->setValidationParameters([$anotherValue]);
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value does not include another value.
     *
     * @return Validator $this object
     */
    public function hasNot($anotherValue)
    {
        $this->setValidationName('hasNot');

        if (array_search($anotherValue, $this->getValue())) {
            $this->setValidationParameters([$anotherValue]);
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value includes all of the values from another collection.
     *
     * @return Validator $this object
     */
    public function hasAll($anotherValue)
    {
        $this->setValidationName('hasAll');

        if (!empty(array_diff($anotherValue, $this->getValue()))) {
            $this->setValidationParameters([$anotherValue]);
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value includes any of the values from another collection.
     *
     * @return Validator $this object
     */
    public function hasAny($anotherValue)
    {
        $this->setValidationName('hasAny');

        if (array_diff($anotherValue, $this->getValue()) == $anotherValue) {
            $this->setValidationParameters([$anotherValue]);
            $this->addError();
        }

        return $this;
    }

    /**
     * Validates that the value includes none of the values from another collection.
     *
     * @return Validator $this object
     */
    public function hasNone($anotherValue)
    {
        $this->setValidationName('hasNone');

        if (array_diff($anotherValue, $this->getValue()) != $anotherValue) {
            $this->setValidationParameters([$anotherValue]);
            $this->addError();
        }

        return $this;
    }

    /// Custom validations

    /**
     * Validates the value with a Validator class or callable provided as an argument.
     *
     * The callable receives $this Validator as a parameter to perform the validation
     * actions such as accessing the validate value, adding errors, halting, etc.
     *
     * @param callable|string $customValidator A callable or Validator::class name
     *          to perform the validation.
     *
     * @return Validator $this Validator.
     */
    public function validateWith($customValidator)
    {
        if (is_string($customValidator)) {
            $customValidator = new $customValidator();
        }

        $customValidator($this);

        return $this;
    }
}
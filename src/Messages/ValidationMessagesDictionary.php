<?php

namespace Haijin\Validations\Messages;

/**
 * Provides a nice DSL to define error messages for ValidationErrors.
 *
 * Example
 *
 *      /// Create a new ValidationMessagesDictionary
 *
 *      // Create a new ValidationMessagesDictionary
 *
 *      $validationMessagesDictionary = new ValidationMessagesDictionary();
 *
 *      // and optionally define default messages in it
 *
 *      $validationMessagesDictionary->withDefaultMessages();
 *
 *      // Define a default validation message when a more specific message is not found
 *
 *      $validationMessagesDictionary->define( function($messages) {
 *
 *          $messages->default( function($validationError) {
 *              return "The attribute '{$validationError->getAttributeName()}' is not valid.";
 *          });
 *
 *      });
 *
 *      // Get the validation message for each validation error
 *
 *      foreach( $validationErrors as $error ) {
 *          print( $validationMessagesDictionary->messageFor( $error ) );
 *          print "\n";
 *      }
 *
 *      /// Define more specific messages
 *
 *      $validationMessagesDictionary->define( function($messages) {
 *
 *          $messages->atValidation( 'notBlank', function($validationError) {
 *              return "The attribute '{$validationError->getAttributeName()}' must contain visible characters.";
 *          });
 *
 *          $messages->atValidation( 'length', function($validationError) {
 *              $params = $validationError->getValidationParameters();
 *
 *              return "The attribute '{$validationError->getAttributeName()}' length must be in the range ({$params[0]}, {$params[1]})";
 *          });
 *      });
 *
 *      // Get the validations messages
 *
 *      foreach( $validationMessagesDictionary as $error ) {
 *          print( $errorsDictionary->messageFor( $error ) );
 *          print "\n";
 *      }
 */
class ValidationMessagesDictionary
{
    /**
     * A map from validation names to callables that format ValidationErrors.
     */
    protected $messageFormattersMap;

    /**
     * An optional callable acting as a default formatter when no other formatter is found for a ValidationError.
     */
    protected $defaultMessageFormatter;

    /// Initializing

    /**
     * Initializes the instance.
     */
    public function __construct()
    {
        $this->messageFormattersMap = [];
        $this->defaultMessageFormatter = null;
    }

    /// Definition

    /**
     * Defines the formatters for ValidationErrors using a DSL.
     *
     * @param callable $definitionCallable A callable using the DSL to define
     *      the formatting of the ValidationsError messages.
     *
     * @return ValidationMessagesDictionary Returns $this instance.
     */
    public function define($definitionCallable)
    {
        $definitionCallable($this);

        return $this;
    }

    /**
     * Defines in $this dictionary the default message formatters.
     *
     * @return ValidationMessagesDictionary Returns $this instance.
     */
    public function withDefaultMessages()
    {
        $messages = $this;

        require __DIR__ . "/defaultValidationsMessages.php";

        return $this;
    }


    /**
     * Defines a callable to format a ValidationError for validations of type $validationName.
     *
     * @param string $validationName The name of the validations which messages will
     *      be formatted using the $formatterCallable.
     * @param callable $formatterCallable A callable that returns the formatted message.
     *
     * @return ValidationMessagesDictionary Returns $this instance.
     */
    public function atValidation($validationName, $formatterCallable)
    {
        $this->messageFormattersMap[$validationName] = $formatterCallable;

        return $this;
    }

    /**
     * Drops the callable that formats a ValidationError for validations of type $validationName.
     *
     * @param string $validationName The name of the validations to drop its
     *      formatter callable.
     *
     * @return ValidationMessagesDictionary Returns $this instance.
     */
    public function dropValidation($validationName)
    {
        unset($this->messageFormattersMap[$validationName]);

        return $this;
    }

    /**
     * Defines a callable to format ValidationError by default, unless a more specific
     *  callable is found for a ValidationError.
     *
     * @param callable $formatterCallable A callable that returns the formatted message.
     *
     * @return ValidationMessagesDictionary Returns $this instance.
     */
    public function default($formatterCallable)
    {
        $this->defaultMessageFormatter = $formatterCallable;

        return $this;
    }

    /**
     * Drops the callable that formats a ValidationError by default.
     *
     * @return ValidationMessagesDictionary Returns $this instance.
     */
    public function dropDefault()
    {
        $this->defaultMessageFormatter = null;

        return $this;
    }

    /// Asking

    /**
     * Returns true if $this dictionary has a default callable defined, false if not.
     *
     * @return bool Returns true if this instance has a default callable defined, false if not.
     */
    public function hasDefault()
    {
        return $this->defaultMessageFormatter !== null;
    }

    /**
     * Returns true if $this dictionary has a message formatter defined for a $validationName, false if not.
     *
     * @param string $validationName The name of the validation.
     *
     * @return bool Returns true if $this dictionary has a message formatter for a $validationName, false if not.
     */
    public function hasMessageAt($validationName)
    {
        return array_key_exists($validationName, $this->messageFormattersMap);
    }

    /**
     * Returns true if $this dictionary has a message formatter for a ValidationError, false if not.
     *
     * @param ValidationError $validationName The ValidationError.
     *
     * @return bool Returns true if $this dictionary has a message formatter for the $validationError, false if not.
     */
    public function hasMessageFor($validationError)
    {
        if ($this->defaultMessageFormatter !== null) {
            return true;
        }

        return $this->hasMessageAt($validationError->getValidationName());
    }

    /// Accessing

    /**
     * Returns the defined callable for a $validationName.
     *
     * @param string $validationName The name of the validation to look up for its callable.
     *
     * @return callable The callable defined for the $validationName.
     */
    protected function messageFormatterAt($validationName)
    {
        return $this->messageFormattersMap[$validationName];
    }

    /**
     * Returns a callable to format an error message for a ValidationError.
     *
     * If $this dictionary has a callable for the ValidationError::validationName
     * that callable is returned. If not the default callable is returned.
     *
     * @param ValidationError $validationError The ValidationError to look up for
     *      its formatter callable.
     *
     * @return callable The callable found for the ValidationError.
     */
    protected function callableFor($validationError)
    {
        $validationName = $validationError->getValidationName();

        if ($this->hasMessageAt($validationName)) {
            return $this->messageFormatterAt($validationName);
        }

        return $this->defaultMessageFormatter;
    }

    /**
     * Returns a formatted message string for a ValidationError.
     * If no formatter is found for the ValidationError a Validation_Message_Not_Found_Exception is raised.
     *
     * @param ValidationError $validationError The ValidationError to be formatted to a end user message string.
     *
     * @return string The message string to the end user for the ValidationError.
     */
    public function messageFor($validationError)
    {
        $this->validateMessagePresenceFor($validationError);

        return $this->callableFor($validationError)($validationError);
    }

    /// Errors

    /**
     * Validates that a message exists for a given ValidationError.
     * Raises a Validation_Message_Not_Found_Exception if not.
     *
     * @param ValidationError $validationError The ValidationError to validate for message presence.
     */
    public function validateMessagePresenceFor($validationError)
    {
        if ($this->hasMessageFor($validationError))
            return;

        return $this->raiseMessageNotFoundError($validationError);
    }

    /**
     * Raises a Validation_Message_Not_Found_Exception.
     *
     * @param ValidationError $validationError The ValidationError that is missing a formatter callable.
     */
    protected function raiseMessageNotFoundError($validationError)
    {
        throw new ValidationMessageNotFoundException($validationError);
    }
}
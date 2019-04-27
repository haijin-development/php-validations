<?php

namespace Haijin\Validations\Messages;

$messages->define(function ($messages) {

    $messages->atValidation('matches', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must match '{$validationError->getValidationParameters()[0]}'.";
    });

    $messages->atValidation('isEmail', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' is not a valid email.";
    });

    $messages->atValidation('==', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must equal {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('!=', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must not equal {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('<', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must be < {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('<=', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must be <= {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('>', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must be > {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('>=', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must be >= {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('~', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must be ~ {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('!~', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must be !~ {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('sameValueAt', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' does not match the attribute '{$validationError->getValidationParameters()[0]}'.";
    });

    $messages->atValidation('has', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must have the element {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('hasNot', function ($validationError) {
        return "The attribute '{$validationError->getAttributePath()}' must not have the element {$validationError->getValidationParameters()[0]}.";
    });

    $messages->atValidation('hasAll', function ($validationError) {
        $elements = join(', ', $validationError->getValidationParameters()[0]);

        return "The attribute '{$validationError->getAttributePath()}' must have all the elements [{$elements}].";
    });

    $messages->atValidation('hasAny', function ($validationError) {
        $elements = join(', ', $validationError->getValidationParameters()[0]);

        return "The attribute '{$validationError->getAttributePath()}' must have any of the elements [{$elements}].";
    });

    $messages->atValidation('hasNone', function ($validationError) {
        $elements = join(', ', $validationError->getValidationParameters()[0]);

        return "The attribute '{$validationError->getAttributePath()}' must have none of the elements [{$elements}].";
    });

    $messages->atValidation('isIn', function ($validationError) {
        $elements = join(', ', $validationError->getValidationParameters()[0]);

        return "The attribute '{$validationError->getAttributePath()}' must be one of [{$elements}].";
    });

    $messages->atValidation('notIn', function ($validationError) {
        $elements = join(', ', $validationError->getValidationParameters()[0]);

        return "The attribute '{$validationError->getAttributePath()}' must be none of [{$elements}].";
    });

    $messages->atValidation('length', function ($validationError) {
        $params = $validationError->getValidationParameters();

        if ($params[0] !== null && $params[1] !== null) {
            $length = "a length between {$params[0]} and {$params[1]}";
        } elseif ($params[0] !== null) {
            $length = "a minimum length of {$params[0]}";
        } elseif ($params[1] !== null) {
            $length = "a maximum length of {$params[1]}";
        }

        return "The attribute '{$validationError->getAttributePath()}' must have {$length}.";
    });

    $messages->default(function ($validationError) {

        $validationName = $validationError->getValidationName();
        $attributePath = $validationError->getAttributePath();


        if (isTypeValidation($validationName)) {
            return formatTypeValidation($attributePath, $validationName);
        }

        if ($match = extractPatternFrom('/^is(.+)$/', $validationName)) {
            $match = strtolower($match);
            return "The attribute '{$attributePath}' must be {$match}.";
        }

        if ($match = extractPatternFrom('/^not(.+)$/', $validationName)) {
            $match = strtolower($match);
            return "The attribute '{$attributePath}' must not be {$match}.";
        }

        return "The attribute '{$attributePath}' is not valid.";
    });

    /// Helper function

    if (!function_exists(
        'Haijin\Validations\Messages\extractPatternFrom')
    ) {

        function extractPatternFrom($pattern, $validationName)
        {
            $matches = [];

            if (preg_match($pattern, $validationName, $matches)) {
                return $matches[1];
            }

            return null;
        }

        function isTypeValidation($validationName)
        {
            $typeValidations = ['isString', 'isInt', 'isFloat', 'isNumber', 'isBoolean', 'isArray', 'isObject'];

            return in_array($validationName, $typeValidations);
        }

        function formatTypeValidation($attributePath, $validationName)
        {
            $match = strtolower(extractPatternFrom('/^is(.+)$/', $validationName));

            if (isVowel($match[0]))
                $joiner = 'an';
            else
                $joiner = 'a';

            return "The attribute '{$attributePath}' must be {$joiner} {$match}.";
        }

        function isVowel($char)
        {
            return in_array($char, ['a', 'e', 'i', 'o', 'u']);
        }

    }

});
<?php

namespace Haijin\Validations;

abstract class CustomValidator extends Validator
{
    abstract public function evaluate();
}
<?php

namespace Haijin\Validations;

abstract class Custom_Validator extends Validator
{
    abstract public function evaluate();
}
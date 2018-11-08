<?php

namespace Haijin\Tests;

trait ValidationsTestBehaviour
{
    use \Haijin\Testing\AllExpectationsTrait;

    protected function assertValidationError($validation_error, $expected_validation)
    {
        $this->assertEquals(
            $expected_validation['value'],
            $validation_error->get_value(),
            "The validation error value does not match the expected value."
        );

        $this->assertEquals(
            $expected_validation['attribute_path'],
            $validation_error->get_attribute_path()->to_string(),
            "The validation error attribute_path does not match the expected attribute_path."
        );

        $this->assertEquals(
            $expected_validation['validation_name'],
            $validation_error->get_validation_name(),
            "The validation error validation_name does not match the expected validation_name."
        );

        $this->assertEquals(
            $expected_validation['validation_parameters'],
            $validation_error->get_validation_parameters(),
            "The validation error validation_parameters does not match the expected validation_parameters."
        );
    }
}
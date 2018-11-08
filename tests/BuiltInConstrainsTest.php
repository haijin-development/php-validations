constraints<?php

use PHPUnit\Framework\TestCase;

use Haijin\Validations\Validator;

/**
 * Tests the use of the built constraintsin validations.
 */
class BuiltInConstraintsTest extends TestCase
{
    use \Haijin\Tests\ValidationsTestBehaviour;

    /// Presence constraints

    public function testIsPresentPasses()
    {
        $object = 'a string';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_present();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsPresentFails()
    {
        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_present();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => '',
            'validation_name' => 'is_present',
            'validation_parameters' => []
        ]);
    }

    public function testIsPresentHalts()
    {
        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_present();

            throw new Exception("is_present should halt its branch");
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => '',
            'validation_name' => 'is_present',
            'validation_parameters' => []
        ]);
    }

    public function testNotPresentPasses()
    {
        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_present();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testNotPresentFails()
    {
        $object = 'a string';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_present();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 'a string',
            'attribute_path' => '',
            'validation_name' => 'not_present',
            'validation_parameters' => []
        ]);
    }

    public function testIsOptionalPasses()
    {
        $object = 'a string';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj ->is_optional() ->is_int();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 'a string',
            'attribute_path' => '',
            'validation_name' => 'is_int',
            'validation_parameters' => []
        ]);
    }

    public function testIsOptionalHalts()
    {
        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_optional();

            throw new Exception( "is_optional should halt and this should not be executed" );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsEmptyPasses()
    {
        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_empty();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsEmptyFails()
    {
        $object = [ 123 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_empty();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [ 123 ],
            'attribute_path' => '',
            'validation_name' => 'is_empty',
            'validation_parameters' => []
        ]);
    }

    public function testNotEmptyPasses()
    {
        $object = [ 'a string' ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_empty();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testNotEmptyFails()
    {
        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_empty();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [],
            'attribute_path' => '',
            'validation_name' => 'not_empty',
            'validation_parameters' => []
        ]);
    }

    public function testIsDefinedPasses()
    {
        $object = "a string";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->assertEquals( $validation_errors, [] );


        $object = [ 123 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->assertEquals( $validation_errors, [] );


        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsDefinedFails()
    {
        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => '',
            'validation_name' => 'is_defined',
            'validation_parameters' => []
        ]);


        $object = "";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => "",
            'attribute_path' => '',
            'validation_name' => 'is_defined',
            'validation_parameters' => []
        ]);


        $object = " \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => " \t\n",
            'attribute_path' => '',
            'validation_name' => 'is_defined',
            'validation_parameters' => []
        ]);


        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [],
            'attribute_path' => '',
            'validation_name' => 'is_defined',
            'validation_parameters' => []
        ]);
    }

    public function testIsDefinedHalts()
    {
        $object = null;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_defined();

            throw new Exception("is_defined should halt its branch");
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => null,
            'attribute_path' => '',
            'validation_name' => 'is_defined',
            'validation_parameters' => []
        ]);
    }

    /// String constraints

    public function testIsBlankPasses()
    {
        $object = "";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_blank();
        });

        $this->assertEquals( $validation_errors, [] );

        $object = " \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_blank();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsBlankFails()
    {
        $object = "abc \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_blank();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => "abc \t\n",
            'attribute_path' => '',
            'validation_name' => 'is_blank',
            'validation_parameters' => []
        ]);
    }

    public function testNotBlankPasses()
    {
        $object = "abc \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_blank();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testNotBlankFails()
    {
        $object = "";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_blank();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => "",
            'attribute_path' => '',
            'validation_name' => 'not_blank',
            'validation_parameters' => []
        ]);


        $object = " \t\n";

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_blank();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => " \t\n",
            'attribute_path' => '',
            'validation_name' => 'not_blank',
            'validation_parameters' => []
        ]);
    }

    public function testMatchesPasses()
    {
        $object = '$10.00';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->matches('/[$]\d+\.\d\d/');
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testMatchesFailes()
    {
        $object = '10.00';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->matches( '/[$]\d+\.\d\d/' );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => "10.00",
            'attribute_path' => '',
            'validation_name' => 'matches',
            'validation_parameters' => [ '/[$]\d+\.\d\d/' ]
        ]);
    }

    /// Types constraints

    public function testIsStringPasses()
    {
        $object = 'a string';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_string();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsStringFails()
    {
        $object = 123;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_string();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 123,
            'attribute_path' => '',
            'validation_name' => 'is_string',
            'validation_parameters' => []
        ]);
    }

    public function testIsIntPasses()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_int();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsIntFails()
    {
        $object = '1';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_int();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => '1',
            'attribute_path' => '',
            'validation_name' => 'is_int',
            'validation_parameters' => []
        ]);
    }    

    public function testIsFloatPasses()
    {
        $object = 1.0;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_float();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsFloatFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_float();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => 'is_float',
            'validation_parameters' => []
        ]);
    }    

    public function testIsNumberPasses()
    {
        $object = 1.0;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_number();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsNumberFails()
    {
        $object = '1.0';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_number();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => '1.0',
            'attribute_path' => '',
            'validation_name' => 'is_number',
            'validation_parameters' => []
        ]);
    }    

    public function testIsBooleanPasses()
    {
        $object = true;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_boolean();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsBooleanFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_boolean();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => 'is_boolean',
            'validation_parameters' => []
        ]);
    }    

    public function testIsArrayPasses()
    {
        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_array();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsArrayFails()
    {
        $object = '';

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_array();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => '',
            'attribute_path' => '',
            'validation_name' => 'is_array',
            'validation_parameters' => []
        ]);
    }    

    public function testIsObjectPasses()
    {
        $object = new stdclass();

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_object();
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsObjectFails()
    {
        $object = [];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_object();
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [],
            'attribute_path' => '',
            'validation_name' => 'is_object',
            'validation_parameters' => []
        ]);
    }    

    /// Comparison

    public function testEqualsPasses()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '=', 1 );
            $obj->is( '==', 1 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testEqualsFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '=', 2 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => '==',
            'validation_parameters' => [ 2 ]
        ]);


        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '==', 2 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => '==',
            'validation_parameters' => [ 2 ]
        ]);
    }


    public function testDiffersPasses()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '!=', 2 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testDiffersFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '!=', 1 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => '!=',
            'validation_parameters' => [ 1 ]
        ]);
    }

    public function testLowerPasses()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '<', 2 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testLowerFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '<', 0 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => '<',
            'validation_parameters' => [ 0 ]
        ]);
    }

    public function testLowerOrEqualPasses()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '<=', 1 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testLowerOrEqualFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '<=', 0 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => '<=',
            'validation_parameters' => [ 0 ]
        ]);
    }

    public function testGreaterPasses()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '>', 0 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testGreaterFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '>', 1 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => '>',
            'validation_parameters' => [ 1 ]
        ]);
    }

    public function testGreaterOrEqualPasses()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '>=', 1 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testGreaterOrEqualFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '>=', 2 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => '>=',
            'validation_parameters' => [ 2 ]
        ]);
    }

    public function testEqualsWithPrecisionPasses()
    {
        $object = 10.00;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '~', 9.99, 0.01 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testEqualsWithPrecisionFails()
    {
        $object = 10.00;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '~', 0.98, 0.01 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 10.00,
            'attribute_path' => '',
            'validation_name' => '~',
            'validation_parameters' => [ 0.98, 0.01 ]
        ]);
    }

    public function testDiffersWithPrecisionPasses()
    {
        $object = 10.00;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '!~', 9.98, 0.01 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testDiffersWithPrecisionFails()
    {
        $object = 10.00;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is( '!~', 9.99, 0.01 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 10.00,
            'attribute_path' => '',
            'validation_name' => '!~',
            'validation_parameters' => [ 9.99, 0.01 ]
        ]);
    }

    /// Inclussion

    public function testHasPasses()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has( 3 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testHasFails()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has( 4 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [ 1, 2, 3 ],
            'attribute_path' => '',
            'validation_name' => 'has',
            'validation_parameters' => [ 4 ]
        ]);
    }    

    public function testHasNotPasses()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_not( 4 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testHasNotFails()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_not( 3 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [ 1, 2, 3 ],
            'attribute_path' => '',
            'validation_name' => 'has_not',
            'validation_parameters' => [ 3 ]
        ]);
    }    

    public function testHasAllPasses()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_all( [ 1, 3 ] );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testHasAllFails()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_all( [ 1, 4 ] );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [ 1, 2, 3 ],
            'attribute_path' => '',
            'validation_name' => 'has_all',
            'validation_parameters' => [ [ 1, 4 ] ]
        ]);
    }    

    public function testHasAnyPasses()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_any( [ 4, 3 ] );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testHasAnyFails()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_any( [ 4, 5 ] );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [ 1, 2, 3 ],
            'attribute_path' => '',
            'validation_name' => 'has_any',
            'validation_parameters' => [ [ 4, 5 ] ]
        ]);
    }    

    public function testHasNonePasses()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_none( [ 4, 5 ] );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testHasNoneFails()
    {
        $object = [ 1, 2, 3 ];

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->has_none( [ 4, 1 ] );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [ 1, 2, 3 ],
            'attribute_path' => '',
            'validation_name' => 'has_none',
            'validation_parameters' => [ [ 4, 1 ] ]
        ]);
    }

    public function testIsInPasses()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_in( [ 1, 2 ] );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testIsInFails()
    {
        $object = 3;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->is_in( [ 1, 2 ] );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 3,
            'attribute_path' => '',
            'validation_name' => 'is_in',
            'validation_parameters' => [ [ 1, 2 ] ]
        ]);
    }

    public function testNotInPasses()
    {
        $object = 3;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_in( [ 1, 2 ] );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testNotInFails()
    {
        $object = 1;

        $validator = new Validator();

        $validation_errors = $validator->validate( $object, function($obj) {
            $obj->not_in( [ 1, 2 ] );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 1,
            'attribute_path' => '',
            'validation_name' => 'not_in',
            'validation_parameters' => [ [ 1, 2 ] ]
        ]);
    }

    /// Length

    public function testLengthPasses()
    {
        $validator = new Validator();

        $validation_errors = $validator->validate( '', function($obj) {
            $obj->length( 0, 3 );
        });

        $this->assertEquals( $validation_errors, [] );        $object = 'abc';


        $validation_errors = $validator->validate( 'abc', function($obj) {
            $obj->length( 1, 3 );
        });

        $this->assertEquals( $validation_errors, [] );
    }

    public function testHasLengthFails()
    {
        $validator = new Validator();

        $validation_errors = $validator->validate( '', function($obj) {
            $obj->length( 1, 3 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => '',
            'attribute_path' => '',
            'validation_name' => 'length',
            'validation_parameters' => [ 1, 3 ]
        ]);


        $validator = new Validator();

        $validation_errors = $validator->validate( 'abcd', function($obj) {
            $obj->length( 1, 3 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => 'abcd',
            'attribute_path' => '',
            'validation_name' => 'length',
            'validation_parameters' => [ 1, 3 ]
        ]);


        $validator = new Validator();

        $validation_errors = $validator->validate( [], function($obj) {
            $obj->length( 1, 3 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [],
            'attribute_path' => '',
            'validation_name' => 'length',
            'validation_parameters' => [ 1, 3 ]
        ]);


        $validator = new Validator();

        $validation_errors = $validator->validate( [ 1, 2, 3, 4 ], function($obj) {
            $obj->length( 1, 3 );
        });

        $this->assertEquals( 1, count($validation_errors) );
        $this->assertValidationError( $validation_errors[0], [
            'value' => [ 1, 2, 3, 4 ],
            'attribute_path' => '',
            'validation_name' => 'length',
            'validation_parameters' => [ 1, 3 ]
        ]);
    }
}
<?php

namespace Swiftery\Test;

use PHPUnit_Framework_TestCase;
use Swiftery\BIC;

class BICTest extends PHPUnit_Framework_TestCase
{

    /** @dataProvider caseProvider */
    public function testSanitize($input, $sanitized)
    {
        $this->assertEquals($sanitized, BIC::sanitize($input));
    }


    /** @dataProvider caseProvider */
    public function testVerify($input, $sanitized, $valid)
    {
        $this->assertEquals($valid, (new BIC($input))->verify());
    }

    /** @dataProvider validCaseProvider */
    public function testGetBICValid($input, $sanitized, $valid, $generatedBIC)
    {
        $this->assertEquals($generatedBIC, (new BIC($input))->getBIC());
    }

    /**
     * @dataProvider invalidCaseProvider
     * @expectedException \Swiftery\Exception\InvalidIdentifierException
     */
    public function testGetBICInvalid($input)
    {
        (new BIC($input))->getBIC();
    }

    /** @dataProvider validCaseProvider */
    public function testGetBankCodeValid(
        $input,
        $sanitized,
        $valid,
        $generatedBIC,
        $bankCode,
        $country,
        $location,
        $branch
    ) {
        $this->assertEquals($bankCode, (new BIC($input))->getBankCode());
    }

    /**
     * @dataProvider invalidCaseProvider
     * @expectedException \Swiftery\Exception\InvalidIdentifierException
     */
    public function testGetBankCodeInvalid($input)
    {
        (new BIC($input))->getBankCode();
    }

    /** @dataProvider validCaseProvider */
    public function testGetCountryValid(
        $input,
        $sanitized,
        $valid,
        $generatedBIC,
        $bankCode,
        $country,
        $location,
        $branch
    ) {
        $this->assertEquals($country, (new BIC($input))->getCountry());
    }

    /**
     * @dataProvider invalidCaseProvider
     * @expectedException \Swiftery\Exception\InvalidIdentifierException
     */
    public function testGetCountryInvalid($input)
    {
        (new BIC($input))->getCountry();
    }

    /** @dataProvider validCaseProvider */
    public function testGetLocationValid(
        $input,
        $sanitized,
        $valid,
        $generatedBIC,
        $bankCode,
        $country,
        $location,
        $branch
    ) {
        $this->assertEquals($location, (new BIC($input))->getLocation());
    }

    /**
     * @dataProvider invalidCaseProvider
     * @expectedException \Swiftery\Exception\InvalidIdentifierException
     */
    public function testGetLocationInvalid($input)
    {
        (new BIC($input))->getLocation();
    }

    /** @dataProvider validCaseProvider */
    public function testGetBranchValid(
        $input,
        $sanitized,
        $valid,
        $generatedBIC,
        $bankCode,
        $country,
        $location,
        $branch
    ) {
        $this->assertEquals($branch, (new BIC($input))->getBranch());
    }

    /**
     * @dataProvider invalidCaseProvider
     * @expectedException \Swiftery\Exception\InvalidIdentifierException
     */
    public function testGetBranchInvalid($input)
    {
        (new BIC($input))->getBranch();
    }

    public function caseProvider()
    {
        return array_merge(
            $this->validCaseProvider(),
            $this->invalidCaseProvider()
        );
    }

    public function validCaseProvider()
    {
        return array(
            //    Input          Sanitized      Valid BIC            Bank    Country Location Branch
            array('essL De 66', 'ESSLDE66', true, 'ESSLDE66XXX', 'ESSL', 'DE', '66', 'XXX'),
            array('ESSLDE66', 'ESSLDE66', true, 'ESSLDE66XXX', 'ESSL', 'DE', '66', 'XXX'),
            array('ESSLDE66XXX', 'ESSLDE66XXX', true, 'ESSLDE66XXX', 'ESSL', 'DE', '66', 'XXX'),
            array('ESSLDE66xyz', 'ESSLDE66XYZ', true, 'ESSLDE66XYZ', 'ESSL', 'DE', '66', 'XYZ'),
        );
    }

    public function invalidCaseProvider()
    {
        return array(
            //    Input                 Sanitized       Valid
            array('asd l fasd;lf09', 'ASDLFASDLF09', false),
            array(' *** *** ', '', false),
        );
    }
}

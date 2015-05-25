<?php

namespace Swiftery\Test;

use PHPUnit_Framework_TestCase;
use Swiftery\Exception\CountryNotFoundException;
use Swiftery\IBAN;

class IBANTest extends PHPUnit_Framework_TestCase
{

    /** @dataProvider caseProvider */
    public function testSanitize($input, $sanitized)
    {
        $this->assertEquals($sanitized, IBAN::sanitize($input));
    }


    /** @dataProvider caseProvider */
    public function testVerify($input, $sanitized, $valid)
    {
        $this->assertEquals($valid, $this->instantiateIBAN($input)->verify());
    }

    /** @dataProvider validCaseProvider */
    public function testGetIBANValid($input, $sanitized, $valid, $generatedIBAN)
    {
        $this->assertEquals($generatedIBAN, $this->instantiateIBAN($input)->getIBAN());
    }

    /**
     * @dataProvider invalidCaseProvider
     * @expectedException \Swiftery\Exception\InvalidIdentifierException
     */
    public function testGetIBANInvalid($input)
    {
        $this->instantiateIBAN($input)->getIBAN();
    }

    /** @dataProvider validCaseProvider */
    public function testGetCountryValid($input, $sanitized, $valid, $generatedIBAN, $country)
    {
        $this->assertEquals($country, $this->instantiateIBAN($input)->getCountry());
    }

    /**
     * @dataProvider invalidCaseProvider
     * @expectedException \Swiftery\Exception\InvalidIdentifierException
     */
    public function testGetCountryInvalid($input)
    {
        $this->instantiateIBAN($input)->getCountry();
    }

    /** @dataProvider validCaseProvider */
    public function testGetBBANValid($input, $sanitized, $valid, $generatedIBAN, $country, $bban)
    {
        $this->assertEquals($bban, $this->instantiateIBAN($input)->getBBAN());
    }

    /**
     * @dataProvider invalidCaseProvider
     * @expectedException \Swiftery\Exception\InvalidIdentifierException
     */
    public function testGetBBANInvalid($input)
    {
        $this->instantiateIBAN($input)->getBBAN();
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
            array(
                'DE79850503003100180568', // Input
                'DE79850503003100180568', // Sanitized
                true, // Valid
                'DE79850503003100180568', // IBAN
                'DE', // Country
                '850503003100180568', // BBAN
            ),
            array(
                'IBAN DE79 8505 0300 - 3100 1805 68 (&^$', // Input
                'DE79850503003100180568', // Sanitized
                true, // Valid
                'DE79850503003100180568', // IBAN
                'DE', // Country
                '850503003100180568', // BBAN
            ),
        );
    }

    public function invalidCaseProvider()
    {
        return array(
            array(
                'asd l fasd;lf09', // Input
                'ASDLFASDLF09', // Sanitized
                false, // Valid
            ),
            array(
                ' *** *** ', // Input
                '', // Sanitized
                false, // Valid
            ),
            array(
                'ZZ80123456789', // Input
                'ZZ80123456789', // Sanitized
                false, // Valid
            ),
            array(
                'DE50123456789', // Input
                'DE50123456789', // Sanitized
                false, // Valid
            ),
        );
    }

    private function instantiateIBAN($iban)
    {
        return new IBAN($iban, $this->createMockConfigProvider());
    }

    /**
     * @return \Swiftery\ConfigProvider\IBANConfigProvider
     */
    private function createMockConfigProvider()
    {
        $mock = $this->getMockBuilder('Swiftery\ConfigProvider\IBANConfigProvider')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getCountry')
            ->will($this->returnCallback(function ($countryCode) {
                return $this->createMockCountryConfig($countryCode);
            }));
        return $mock;
    }

    /**
     * @param string $countryCode
     * @return \Swiftery\ConfigProvider\Country
     */
    private function createMockCountryConfig($countryCode)
    {
        $map = array(
            'DE' => array(
                'getIBANStructureAsRegex' => '/^DE(\d{2})(\d{8})(\d{10})$/',
                'getIBANLength' => 22,
            ),
        );

        if (!isset($map[$countryCode])) {
            throw new CountryNotFoundException;
        }

        $mock = $this->getMockBuilder('Swiftery\ConfigProvider\Country')
            ->disableOriginalConstructor()
            ->getMock();

        foreach ($map[$countryCode] as $method => $value) {
            $mock->method($method)->willReturn($value);
        }

        return $mock;
    }
}

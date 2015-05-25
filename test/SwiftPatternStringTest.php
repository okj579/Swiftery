<?php


namespace Swiftery\Test;

use PHPUnit_Framework_TestCase;
use Swiftery;

class SwiftPatternStringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider caseProvider
     */
    public function testToRegex($input, $expected)
    {
        $this->assertEquals($expected, Swiftery\SwiftPatternString::toRegex($input));
    }

    public function caseProvider()
    {
        return array(
            array('2!n', '/^(\d{2})$/'),
            array('2a', '/^([A-Z]{0,2})$/'),
            array('12!c', '/^([A-Za-z0-9]{12})$/'),
            array('1!e', '/^(\s{1})$/'),
            array('LITERAL 2!n1e2!c', '/^LITERAL (\d{2})(\s{0,1})([A-Za-z0-9]{2})$/'),
        );
    }
}

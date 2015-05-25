<?php

namespace Swiftery\Test;

use Swiftery\IBANFactory;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /** @var IBANFactory */
    private $ibanFactory;

    public function setUp()
    {
        $this->ibanFactory = new IBANFactory;
    }

    /**
     * @dataProvider ibanProvider
     */
    public function testIBAN($input)
    {
        $iban = $this->ibanFactory->create($input);
        $this->assertTrue($iban->verify(), $input);
    }

    public function ibanProvider()
    {
        $exampleIBANs = array();

        $file = fopen(__DIR__ . '/../registry.txt', 'r');
        fgets($file);
        while ($line = fgetcsv($file, null, "\t")) {
            $countryCode = $line[1];
            $example = $line[13];

            foreach (preg_split('/[^A-Z0-9]+/', trim($example)) as $iban) {
                if (!strlen($iban)) print_r($line);
                $exampleIBANs[] = array($iban);
            }

//            if (IBANProvider::supports($countryCode)) {
//                for ($i = 0; $i < 20; $i++) {
//                    $exampleIBANs[] = array(IBANProvider::getIBAN($countryCode));
//                }
//            }
        }
        fclose($file);

        return $exampleIBANs;
    }
}

<?php


namespace Swiftery\ConfigProvider;


use Swiftery\Exception\CountryNotFoundException;

class IBANFileConfigProvider extends IBANRuntimeConfigProvider implements IBANConfigProvider {

    /** @inheritdoc */
    public function __construct($filename)
    {
        $file = fopen($filename, 'r');
        fgets($file); // Throw away headers
        while ($line = fgetcsv($file, null, "\t", '"')) {
            $this->countries[$line[1]] = new Country(
                trim($line[1]),  // Code
                trim($line[0]),  // Name
                trim($line[11]), // IBAN structure
                (int)$line[12], // IBAN length
                substr($line[1], 0, 3) === 'Yes' // SEPA
            );
        }
    }

}

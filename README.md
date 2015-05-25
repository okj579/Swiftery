# Swiftery
Swiftery is a validator for SWIFT identifiers like IBAN and BIC.

## Usage
Read the source, Luke! But here are some examples too:

### BICs
````php
$bic = new BIC('BANKDE66');
if ($bic->isValid()) {
    $bic->getBIC(); // 'BANKDE66XXX'
    $bic->getBankCode(); // 'BANK'
    $bic->getCountry(); // 'DE'
    $bic->getLocation(); // '66'
    $bic->getBranch(); // 'XXX'
}
````

Though the default branch code of XXX is optional, Swiftery creates it if missing.

### IBANs

````php
$iban = new IBAN('IBAN DE79 8505 0300 - 3100 1805 68 (&^$', $configProvider);
if ($iban->isValid()) {
    $iban->getIBAN(); // 'DE79850503003100180568'
    $iban->getCountry(); // 'DE'
    $iban->getBBAN(); // '850503003100180568'
    $iban->getBBANParts(); // ['85050300', '3100180568']
}
````

The `$configProvider` here provides the checker with country-specific format information. To supply this only once, use
`IBANFactory`. It can also provide a default implementation:

````php
$factory = new IBANFactory;
$iban = $factory->create('DE79850503003100180568');
````

This implementation is based on `registry.txt` from the [SWIFT registry][1] with some corrections.

[1]: http://www.swift.com/products_services/bic_and_iban_format_registration_iban_format_r

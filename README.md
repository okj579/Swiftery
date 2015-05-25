# Swiftery [![Build Status](https://travis-ci.org/okj579/swiftery.svg?branch=master)](https://travis-ci.org/okj579/swiftery)
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
`getBBANParts()` returns the constituent parts of the national BBAN. For example, a German IBAN would return the routing
number (BLZ) and account number, while a Brazilian IBAN would return the national bank code, branch code, account
number, account type, and account owner number. (See [here][Wikipedia] for a list of formats)

The `$configProvider` here provides the checker with country-specific format information. To supply this only once, use
`IBANFactory`. It can also provide a default implementation:

````php
$factory = new IBANFactory;
$iban = $factory->create('DE79850503003100180568');
````

This implementation is based on `registry.txt` from the [SWIFT registry][SWIFT] with some corrections.

[Wikipedia]: https://en.wikipedia.org/wiki/International_Bank_Account_Number#IBAN_formats_by_country
[SWIFT]: http://www.swift.com/products_services/bic_and_iban_format_registration_iban_format_r

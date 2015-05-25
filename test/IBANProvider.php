<?php

namespace Swiftery\Test;

class IBANProvider extends \Faker\Provider\Payment
{
    public static function supports($countryCode)
    {
        return isset(parent::$ibanFormats[$countryCode]);
    }

    public static function getIBAN($countryCode)
    {
        return parent::iban($countryCode);
    }
}

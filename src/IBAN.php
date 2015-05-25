<?php
namespace Swiftery;

use Swiftery\ConfigProvider\IBANConfigProvider;
use Swiftery\Exception\NoConfigProviderException;
use Swiftery\Exception\CountryNotFoundException;
use Swiftery\Traits\IdentifierTrait;

class IBAN
{
    /** @var IBANConfigProvider $configProvider */
    private $configProvider;

    private $countryCode;
    private $checkDigits;
    private $bban;
    private $countryConfig;

    use IdentifierTrait {
        sanitize as genericSanitize;
    }

    public function __construct($iban, $configProvider)
    {
        $this->identifier = static::sanitize($iban);
        $this->configProvider = $configProvider;
    }

    public static function sanitize($iban)
    {
        $iban = self::genericSanitize($iban);
        $iban = preg_replace('/^IBAN/', '', $iban);
        return $iban;
    }

    /**
     * Verifies the identifier and returns it's validity
     *
     * @param bool $strict If strict mode is off, country-specific checks are ignored for unknown countries. (Default: true)
     * @return bool
     */
    public function verify($strict = true)
    {
        if (!isset($this->configProvider)) {
            throw new NoConfigProviderException;
        }

        $this->countryCode = substr($this->identifier, 0, 2);
        $this->checkDigits = substr($this->identifier, 2, 2);
        $this->bban = substr($this->identifier, 4);


        if (!$this->verifyCheckDigits()) {
            return false;
        }

        try {
            $this->countryConfig = $this->configProvider->getCountry($this->countryCode);
        } catch (CountryNotFoundException $e) {
            // We cannot do any country-based tests. If strict mode is on, this means a failure.
            return !$strict;
        }

        if (strlen($this->identifier) !== $this->countryConfig->getIBANLength()) {
            return false;
        }

        if (!preg_match($this->countryConfig->getIBANStructureAsRegex(), $this->identifier)) {
            return false;
        }

        return true;
    }

    private function verifyCheckDigits()
    {
        $checkString = $this->bban . $this->countryCode . '00';
        $checkString = preg_replace_callback('/[A-Z]/', function ($matches) {
            return self::letterToDigits($matches[0]);
        }, $checkString);

        $checkDigits = 98 - self::mod97($checkString);
        $checkDigits = str_pad($checkDigits, 2, '0', STR_PAD_LEFT);

        return $checkDigits === $this->checkDigits;
    }

    private static function letterToDigits($letter)
    {
        $letter = strtoupper($letter);
        $number = ord($letter) - ord('A') + 10;
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }

    private static function mod97($numberString)
    {
        // How big a (decimal) number can we perform operations on
        $max_length = strlen(PHP_INT_MAX) - 1;

        $checksum = '';
        $i = 0;
        while ($i < strlen($numberString)) {
            $length = $max_length - strlen($checksum);
            $checksum .= substr($numberString, $i, $length);
            $checksum %= 97;
            $i += $length;
        }
        return str_pad($checksum, 2, '0', STR_PAD_LEFT);
    }

    public function getIBAN()
    {
        $this->assertValid();
        return $this->identifier;
    }

    public function getCountry()
    {
        $this->assertValid();
        return $this->countryCode;
    }

    public function getBBAN()
    {
        $this->assertValid();
        return $this->bban;
    }

    public function getBBANParts()
    {
        $this->assertValid();
        $pattern = $this->configProvider->getCountry($this->countryCode)->getIBANStructureAsRegex();
        preg_match($pattern, $this->identifier, $parts);

        return array_slice($parts, 2);
    }

}

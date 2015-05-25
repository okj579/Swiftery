<?php


namespace Swiftery\ConfigProvider;


use Swiftery\Exception\CountryNotFoundException;

interface IBANConfigProvider {
    /**
     * @param IBANConfigProvider $fallbackProvider
     * @returns IBANConfigProvider $fallbackProvider
     */
    public function setFallback(IBANConfigProvider $fallbackProvider);


    /**
     * @param string $country
     * @return Country
     * @throws CountryNotFoundException
     */
    public function getCountry($country);
}

<?php


namespace Swiftery\ConfigProvider;


use Swiftery\Exception\CountryNotFoundException;

class IBANRuntimeConfigProvider implements IBANConfigProvider {

    protected $fallbackProvider;
    protected $countries;

    /** @inheritdoc */
    public function setFallback(IBANConfigProvider $fallbackProvider) {
        // Remove Cached nulls
        $this->countries = array_filter($this->countries, 'is_null');

        // Set fallback
        $this->$fallbackProvider = $fallbackProvider;
        return $fallbackProvider;
    }

    /** @inheritdoc */
    public function getCountry($country) {
        if (!isset($this->countries[$country]) && $this->fallbackProvider) {
            try {
                $this->countries[$country] = $this->fallbackProvider->getCountry($country);
            } catch (CountryNotFoundException $e) {
                $this->countries[$country] = null;
            }
        }
        if (empty($this->countries[$country])) {
            throw new CountryNotFoundException("Country '$country' not found");
        }
        return $this->countries[$country];
    }
}

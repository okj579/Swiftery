<?php


namespace Swiftery;


use Swiftery\Exception\InvalidIdentifierException;
use Swiftery\Traits\IdentifierTrait;

class BIC
{

    use IdentifierTrait;
    private $parts;

    public function verify()
    {
        if (!preg_match('/^([A-Z]{4})([A-Z]{2})([A-Z0-9]{2})([A-Z0-9]{3})?$/', $this->identifier, $parts)) {
            return false;
        }
        $this->parts = array(
            'bankCode' => $parts[1],
            'country' => $parts[2],
            'location' => $parts[3],
            'branch' => isset($parts[4]) ? $parts[4] : 'XXX',
        );
        return true;
    }

    /**
     * @return string
     * @throws InvalidIdentifierException
     */
    public function getBIC()
    {
        $this->assertValid();
        return join('', $this->parts);
    }

    /**
     * @return string
     * @throws InvalidIdentifierException
     */
    public function getBankCode()
    {
        $this->assertValid();
        return $this->parts['bankCode'];
    }

    /**
     * @return string
     * @throws InvalidIdentifierException
     */
    public function getCountry()
    {
        $this->assertValid();
        return $this->parts['country'];
    }

    /**
     * @return string
     * @throws InvalidIdentifierException
     */
    public function getLocation()
    {
        $this->assertValid();
        return $this->parts['location'];
    }

    /**
     * @return string
     * @throws InvalidIdentifierException
     */
    public function getBranch()
    {
        $this->assertValid();
        return $this->parts['branch'];
    }

}

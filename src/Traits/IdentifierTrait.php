<?php


namespace Swiftery\Traits;


use Swiftery\Exception\InvalidIdentifierException;

trait IdentifierTrait
{

    protected $identifier;
    protected $valid;

    /**
     * @param string $identifier
     */
    public function __construct($identifier)
    {
        $this->identifier = static::sanitize($identifier);
    }

    /**
     * Sanitizes input prior to validation
     *
     * @param string $identifier
     * @return string
     */
    public static function sanitize($identifier)
    {
        $identifier = strtoupper($identifier);
        $identifier = preg_replace('/[^A-Z0-9]/', '', $identifier);
        return $identifier;
    }

    /**
     * Verifies the identifier and returns it's validity
     *
     * @return bool
     */
    abstract function verify();

    /**
     * @return bool
     */
    public function isValid()
    {
        if (is_null($this->valid)) {
            $this->valid = $this->verify();
        }
        return $this->valid;
    }

    protected function assertValid()
    {
        if (!$this->isValid()) {
            throw new InvalidIdentifierException;
        }
    }

}

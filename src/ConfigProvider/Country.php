<?php


namespace Swiftery\ConfigProvider;


use Swiftery\SwiftPatternString;

class Country {

    private $code;
    private $name;
    private $IBANStructure;
    private $IBANLength;
    private $isSEPA;

    private $IBANStructureRegex;

    /**
     * @param string $code
     * @param string $name
     * @param string $IBANStructure
     * @param int $IBANLength
     * @param bool $isSEPA
     */
    public function __construct($code, $name, $IBANStructure, $IBANLength, $isSEPA) {
        $this->code = $code;
        $this->name = $name;
        $this->IBANStructure = $IBANStructure;
        $this->IBANLength = $IBANLength;
        $this->isSEPA = $isSEPA;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIBANStructure()
    {
        return $this->IBANStructure;
    }
    /**
     * @return string
     */
    public function getIBANStructureAsRegex()
    {
        if ($this->IBANStructureRegex === null) {
            $this->IBANStructureRegex = SwiftPatternString::toRegex($this->IBANStructure);
        }
        return $this->IBANStructureRegex;
    }

    /**
     * @return int
     */
    public function getIBANLength()
    {
        return $this->IBANLength;
    }

    /**
     * @return boolean
     */
    public function isSEPA()
    {
        return $this->isSEPA;
    }

}

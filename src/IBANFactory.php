<?php


namespace Swiftery;

use Swiftery\ConfigProvider\IBANConfigProvider;
use Swiftery\ConfigProvider\IBANFileConfigProvider;

class IBANFactory
{
    /**
     * @var IBANConfigProvider
     */
    private $configProvider;

    /**
     * @param IBANConfigProvider $configProvider
     */
    public function __construct(IBANConfigProvider $configProvider = null)
    {
        $this->configProvider = $configProvider ?: self::defaultConfigProvider();
    }

    /**
     * @param string $iban
     * @return IBAN
     */
    public function create($iban)
    {
        return new IBAN($iban, $this->configProvider);
    }

    /**
     * @return IBANFileConfigProvider
     */
    public static function defaultConfigProvider()
    {
        return new IBANFileConfigProvider(__DIR__ . '/../registry.txt');
    }
}

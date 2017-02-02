<?php

use Mediaopt\Ogone\Sdk\Main as sdkMain;
use Monolog\Handler\StreamHandler;

/**
 * abstract factory
 */
abstract class mo_ogone__abstract_factory
{

    /**
     *
     * @var \oxConfig
     */
    protected $oxConfig;

    /**
     *
     * @var \oxLang
     */
    protected $oxLang;

    /**
     *
     * @var \oxSession
     */
    protected $oxSession;

    /**
     * get oxconfig
     * @return oxConfig
     */
    public function getOxConfig()
    {
        if ($this->oxConfig !== null) {
            return $this->oxConfig;
        }
        return $this->oxConfig = oxRegistry::get('oxConfig');
    }

    /**
     * get sdkmain
     * @return sdkMain
     */
    public function getSdkMain()
    {
        return sdkMain::getInstance();
    }

    public function getOxLang()
    {
        if ($this->oxLang !== null) {
            return $this->oxLang;
        }
        return $this->oxLang = oxRegistry::getLang();
    }

    public function getOxSession()
    {
        if ($this->oxSession !== null) {
            return $this->oxSession;
        }
        return $this->oxSession = oxRegistry::getSession();
    }

    /**
     * get current user
     * @return \oxUser
     */
    public function getOxUser()
    {
        return $this->getOxConfig()->getUser();
    }

    /**
     * get adapter main
     * @return \Mediaopt\Ogone\Adapter\Oxid_5\Main
     */
    public function getAdapterMain()
    {
        return $this->getSdkMain()->getAdapter();
    }

    /**
     * return iso-code for country
     *
     * @param string $oxCountryId
     * @return string
     */
    public function getCountryCode($oxCountryId)
    {
        $country = oxNew('oxcountry');
        if ($country->load($oxCountryId)) {
            return $country->oxcountry__oxisoalpha3->value;
        }
        return null;
    }

    /**
     * get logger
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if ($this->logger !== null) {
            //update processors
            return $this->logger;
        }
        $logger = oxNew('mo_ogone__logger', 'mo_ogone');
        $logFile = $this->getLogFilePath();
        $streamHandler = new StreamHandler($logFile, $this->getOxConfig()->getShopConfVar('mo_ogone__logLevel'));
        $logger->pushHandler($streamHandler);
        return $this->logger = $logger;
    }

    /**
     * build log file path
     * @return type
     */
    public function getLogFilePath()
    {
        return $this->getOxConfig()->getLogsDir() . 'mo_ogone-' . date('Y-m', time()) . '.log';
    }
}

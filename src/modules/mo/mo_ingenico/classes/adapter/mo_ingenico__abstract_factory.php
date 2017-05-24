<?php

use Mediaopt\Ingenico\Sdk\Main as sdkMain;

/**
 * abstract factory
 */
abstract class mo_ingenico__abstract_factory
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
     *
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

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
     * @return \Mediaopt\Ingenico\Adapter\Oxid_5\Main
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
     * @return \Psr\Log\LoggerInterface;
     * @throws \InvalidArgumentException
     */
    public function getLogger()
    {
        if ($this->logger !== null) {
            //update processors
            return $this->logger;
        }
        return $this->logger = oxNew('mo_ingenico__helper')->getLogger();
    }
}

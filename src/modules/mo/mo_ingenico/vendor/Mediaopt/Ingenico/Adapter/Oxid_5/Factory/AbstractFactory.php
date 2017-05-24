<?php

namespace Mediaopt\Ingenico\Adapter\Oxid_5\Factory;

use Mediaopt\Ingenico\Sdk\Main as sdkMain;

/**
 * abstract factory
 */
abstract class AbstractFactory
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
     * @var sdkMain
     */
    protected $sdkMain;

    /**
     * constructor
     * @param sdkMain $sdkMain
     * @param \oxConfig $oxConfig
     * @param \oxLang $oxLang
     * @param \oxSession $oxSession
     */
    public function __construct(sdkMain $sdkMain, \oxConfig $oxConfig, \oxLang $oxLang, \oxSession $oxSession)
    {
        $this->sdkMain = $sdkMain;
        $this->oxConfig = $oxConfig;
        $this->oxLang = $oxLang;
        $this->oxSession = $oxSession;
    }

    /**
     * get oxconfig
     * @return \oxConfig
     */
    public function getOxConfig()
    {
        return $this->oxConfig;
    }

    /**
     * get sdkmain
     * @return sdkMain
     */
    public function getSdkMain()
    {
        return $this->sdkMain;
    }

    public function getOxLang()
    {
        return $this->oxLang;
    }

    public function getOxSession()
    {
        return $this->oxSession;
    }

    public function setOxSession(\oxSession $oxSession)
    {
        $this->oxSession = $oxSession;
    }

    public function setOxLang(\oxLang $oxLang)
    {
        $this->oxLang = $oxLang;
    }

    /**
     * set oxconfig
     * @param \oxConfig $oxConfig
     */
    public function setOxConfig(\oxConfig $oxConfig)
    {
        $this->oxConfig = $oxConfig;
    }

    /**
     * set sdkmain
     * @param sdkMain $sdkMain
     */
    public function setSdkMain(sdkMain $sdkMain)
    {
        $this->sdkMain = $sdkMain;
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

}

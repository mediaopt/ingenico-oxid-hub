<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

use Mediaopt\Ogone\Sdk\Main as sdkMain;

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
     * @var sdkMain
     */
    protected $sdkMain;
    
    /**
     * constructor
     * @param sdkMain $sdkMain
     * @param \oxConfig $oxConfig
     */
    public function __construct(sdkMain $sdkMain, \oxConfig $oxConfig)
    {
        $this->sdkMain = $sdkMain;
        $this->oxConfig = $oxConfig;
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
    protected function getOxUser()
    {
        return $this->getOxConfig()->getUser();
    }
    
    /**
     * get adapter main
     * @return \Mediaopt\Ogone\Adapter\Oxid_5\Main
     */
    protected function getAdapterMain()
    {
        return $this->getSdkMain()->getAdapter();
    }

    /**
     * return iso-code for country
     * 
     * @param string $oxCountryId
     * @return string 
     */
    protected function getCountryCode($oxCountryId)
    {
        $country = oxNew('oxcountry');
        if ($country->load($oxCountryId)) {
            return $country->oxcountry__oxisoalpha3->value;
        }
        return null;
    }

}

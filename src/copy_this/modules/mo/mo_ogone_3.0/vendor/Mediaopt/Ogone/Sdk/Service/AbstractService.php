<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Client;
use Mediaopt\Ogone\Sdk\Helper;
use Mediaopt\Ogone\Sdk\Config;

abstract class AbstractService
{

    protected $adapter;
    
    /**
     *
     * @var Client
     */
    protected $client;
    
    /**
     *
     * @var Helper
     */
    protected $helper;
    
    public function __construct($adapter, Client $client, Helper $helper)
    {
        $this->adapter = $adapter;
        $this->client = $client;
        $this->helper = $helper;
    }
    
    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getClient()
    {
        return $this->client;
    }

     /**
     * 
     * @return Config
     */
    protected function getConfig()
    {
        return $this->getAdapter()->getFactory('Config')->build();
    }
    
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }
    
    protected function getApiHostUrl()
    {
        return $this->getConfig()->getIsLiveMode() ? self::API_HOST_URL__PROD : self::API_HOST_URL__DEV;
    }
    
    public function getHelper()
    {
        return $this->helper;
    }
}
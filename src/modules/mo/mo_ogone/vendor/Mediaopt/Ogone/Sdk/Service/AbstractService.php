<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Client;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

abstract class AbstractService
{

    protected $adapter;
    
    /**
     *
     * @var Client
     */
    protected $client;

    public function __construct($adapter, Client $client)
    {
        $this->adapter = $adapter;
        $this->client = $client;
    }
    
    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function call($url, RequestParameters $params)
    {
        return $this->getClient()->call($url, $params->getParams());
    }

}
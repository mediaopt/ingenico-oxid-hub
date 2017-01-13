<?php

namespace Mediaopt\Ogone\Sdk;

use Mediaopt\Ogone\Sdk\Model\AbstractModel;
use Mediaopt\Ogone\Sdk\Service\AbstractService;

class Main
{
    
    static protected $instance = null;
    
    protected $adapter = null;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger = null;
    
    /**
     * 
     * @return Main
     */
    static public function getInstance()
    {
        if(self::$instance !== null) {
            return self::$instance;
        }
        return self::$instance = new Main();
    }
    
    public function getAdapter()
    {
        if ($this->adapter !== null) {
           return $this->adapter; 
        }
        
        $adapterEnvironment = $this->getAdapterEnvironment();
        $adapterClassName = 'Mediaopt\Ogone\Adapter\\' . $adapterEnvironment . '\Main';
        return $this->adapter = new $adapterClassName($this);
    }
    
    /**
     * get adapter name from environment constant
     *
     * @return name 
     */
    protected function getAdapterEnvironment()
    {
      // mbe: TODO
        if (defined('MO_OGONE__ADAPTER_ENVIRONMENT')) {
            return MO_OGONE__ADAPTER_ENVIRONMENT;
        }

        if (defined('OX_BASE_PATH')) {
            return 'Oxid_5';
        }
    }
    
    /**
     * get model by submitted type
     *
     * @param string $type
     * @return AbstractModel
     */
    public function getModel($type)
    {
        $name = __NAMESPACE__ . '\Model\\' . $type;

        return new $name();
    }

    /**
     * get service by submitted type
     *
     * @param string $type
     * @return AbstractService 
     */
    public function getService($type)
    {
        //PHP doesn't resolve a class from variable with relative namespace ! prepend full namespace...
        $name = __NAMESPACE__ . '\Service\\' . ucfirst($type);
        return new $name($this->getAdapter(), $this->getClient());
    }
    
    public function getClient()
    {
        return new Client($this->getLogger());
    }

    /**
     * 
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->getAdapter()->getLogger();
    }
}

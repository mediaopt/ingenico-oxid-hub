<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5;

use Mediaopt\Ogone\Adapter\Oxid_5\Factory\AbstractFactory;
use Mediaopt\Ogone\Sdk\Main as sdkMain;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\TagProcessor;
use Psr\Log\LoggerInterface;

/**
 * main adapter class
 */
class Main
{
    /**
     * SDK-container
     * @var Sdk\Main;
     */
    protected $sdkMain;

    /**
     * @var \oxConfig
     */
    protected $oxConfig = null;
    
    /**
     *
     * @var LoggerInterface
     */
    protected $logger;
    

    /**
     * constructor
     * @param sdkMain $sdkMain
     */
    public function __construct(sdkMain $sdkMain)
    {
        $this->sdkMain = $sdkMain;
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
     * get oxconfig
     * @return \oxConfig
     */
    public function getOxConfig()
    {
        if ($this->oxConfig !== null) {
            return $this->oxConfig;
        }
        return $this->oxConfig = \oxRegistry::get('oxConfig');
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
     * set oxconfig
     * @param \oxConfig $oxConfig
     */
    public function setOxConfig(\oxConfig $oxConfig)
    {
        $this->oxConfig = $oxConfig;
    }

    /**
     * return factory
     * 
     * @param string $type
     * @return AbstractFactory
     */
    public function getFactory($type)
    {
        $name = __NAMESPACE__ . '\Factory\\' . $type;
        return new $name($this->getSdkMain(), $this->getOxConfig());
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
        $logger = new Logger('mo_ogone_3.0');
        $logFile = $this->getLogFilePath();
        $streamHandler = new StreamHandler($logFile, $this->getOxConfig()->getShopConfVar('mo_ogone_3.0__logLevel'));
        $logger->pushHandler($streamHandler);
        return $this->logger = $logger;
    }
    
    /**
     * set logger
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * build log file path
     * @return type
     */
    public function getLogFilePath()
    {
        return $this->getOxConfig()->getLogsDir() . 'mo_ogone_3.0-' . date('Y-m', time()) . '.log';
    }
    
}

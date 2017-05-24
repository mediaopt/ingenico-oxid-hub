<?php

namespace Mediaopt\Ingenico\Adapter\Oxid_5;

use Mediaopt\Ingenico\Adapter\Oxid_5\Factory\AbstractFactory;
use Mediaopt\Ingenico\Sdk\Main as sdkMain;
use Psr\Log\LoggerInterface;

/**
 * main adapter class
 */
class Main
{

    /**
     * SDK-container
     * @var sdkMain;
     */
    protected $sdkMain;

    /**
     * @var \oxConfig
     */
    protected $oxConfig;

    /**
     * @var \oxLang
     */
    protected $oxLang;

    /**
     * @var \oxSession
     */
    protected $oxSession;

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

    public function getOxLang()
    {
        if ($this->oxLang !== null) {
            return $this->oxLang;
        }
        return $this->oxLang = \oxRegistry::getLang();
    }

    public function getOxSession()
    {
        if ($this->oxSession !== null) {
            return $this->oxSession;
        }
        return $this->oxSession = \oxRegistry::getSession();
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
        return new $name($this->getSdkMain(), $this->getOxConfig(), $this->getOxLang(), $this->getOxSession());
    }

    /**
     * get logger
     * @return LoggerInterface
     * @throws \InvalidArgumentException
     */
    public function getLogger()
    {
        if ($this->logger !== null) {
            //update processors
            return $this->logger;
        }
        $logger = oxNew('mo_ingenico__helper')->getLogger();
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
     * @return string
     */
    public function getLogFilePath()
    {
        return $this->getOxConfig()->getLogsDir() . 'mo_ingenico-' . date('Y-m', time()) . '.log';
    }

}

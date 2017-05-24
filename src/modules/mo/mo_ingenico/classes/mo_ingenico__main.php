<?php

/**
 * $Id: mo_ingenico__main.php 7 2012-12-12 10:44:58Z martin $ 
 */

use Mediaopt\Ingenico\Sdk\Main;

class mo_ingenico__main
{

    static protected $instance;

    static public function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    protected function __construct()
    {
        $this->ingenicoConfig = null;
    }

    public function getIngenicoConfig()
    {
        if (null === $this->ingenicoConfig) {
            $configFile = __DIR__ . '/../config.php';
            $this->ingenicoConfig = new mo_ingenico__config($configFile, Main::getInstance()->getLogger());
        }
        return $this->ingenicoConfig;
    }

}

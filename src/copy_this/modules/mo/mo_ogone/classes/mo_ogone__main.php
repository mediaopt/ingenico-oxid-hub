<?php

/**
 * $Id: mo_ogone__main.php 7 2012-12-12 10:44:58Z martin $ 
 */

use Mediaopt\Ogone\Sdk\Main;

class mo_ogone__main
{

    static protected $instance;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    protected function __construct()
    {
        $this->ogoneConfig = null;
    }

    public function getOgoneConfig()
    {
        if (!isset($this->ogoneConfig)) {
            $configFile = realpath(dirname(__FILE__) . '/..') . '/config.php';
            $this->ogoneConfig = new mo_ogone__config($configFile, Main::getInstance()->getLogger());
        }
        return $this->ogoneConfig;
    }

}

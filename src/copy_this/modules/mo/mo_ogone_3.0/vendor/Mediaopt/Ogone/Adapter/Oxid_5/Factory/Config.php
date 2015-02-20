<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

/**
 * cofig factory
 */
class Config extends AbstractFactory
{
    /**
     * populate config model
     * @return \Mediaopt\Ogone\Sdk\Model\Config
     */
    public function build()
    {
        /*@var $model \Mediaopt\Ogone\Sdk\Model\Config */
        $model = $this->getSdkMain()->getModel('Config');
        $model->setIsLiveMode((bool)$this->getOxConfig()->getShopConfVar('mo_ogone_3.0__isLiveMode'));
        
        return $model;
    }
}

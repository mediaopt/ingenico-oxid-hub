<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

/**
 * $Id: $
 */
class SHASettings extends AbstractFactory
{
        /**
     * populate SHASettings model
     * @return \Mediaopt\Ogone\Sdk\Model\SHASettings
     */
    public function build()
    {
        /*@var $model \Mediaopt\Ogone\Sdk\Model\SHASettings */
        $model = $this->getSdkMain()->getModel('SHASettings');
        $model->setSHAInKey($this->oxConfig->getConfigParam('ogone_sSecureKeyIn'));
        $model->setSHAOutKey($this->oxConfig->getConfigParam('ogone_sSecureKeyOut'));
        $model->setSHAAlgorithm($this->getOxConfig()->getConfigParam('ogone_sHashingAlgorithm'));
        $model->setSHAOutParameters(\mo_ogone__main::getInstance()->getOgoneConfig()->shaOutParameters);
        return $model;
    }
}

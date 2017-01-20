<?php

use Mediaopt\Ogone\Sdk\Model\SHASettings;

class mo_ogone__sha_settings extends mo_ogone__abstract_factory
{
    /**
     * populate SHASettings model
     * @return SHASettings
     */
    public function build()
    {
        $model = new SHASettings();
        $model->setSHAInKey($this->getOxConfig()->getConfigParam('ogone_sSecureKeyIn'));
        $model->setSHAOutKey($this->getOxConfig()->getConfigParam('ogone_sSecureKeyOut'));
        $model->setSHAAlgorithm($this->getOxConfig()->getConfigParam('ogone_sHashingAlgorithm'));
        $model->setSHAOutParameters(\mo_ogone__main::getInstance()->getOgoneConfig()->shaOutParameters);
        $model->setSHAOutParametersAliasGateway(\mo_ogone__main::getInstance()->getOgoneConfig()->shaOutParametersAliasGateway);
        $model->setSHAOutParametersHostedTokenizationPage(\mo_ogone__main::getInstance()->getOgoneConfig()->shaOutParametersHostedTokenization);
        return $model;
    }
}

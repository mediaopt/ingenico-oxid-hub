<?php

use Mediaopt\Ingenico\Sdk\Model\SHASettings;

class mo_ingenico__sha_settings extends mo_ingenico__abstract_factory
{
    /**
     * populate SHASettings model
     * @return SHASettings
     */
    public function build()
    {
        $model = new SHASettings();
        $model->setSHAInKey($this->getOxConfig()->getConfigParam('ingenico_sSecureKeyIn'));
        $model->setSHAOutKey($this->getOxConfig()->getConfigParam('ingenico_sSecureKeyOut'));
        $model->setSHAAlgorithm($this->getOxConfig()->getConfigParam('ingenico_sHashingAlgorithm'));
        $model->setIsLiveMode($this->getOxConfig()->getConfigParam('mo_ingenico__isLiveMode'));
        $model->setSHAOutParameters(\mo_ingenico__main::getInstance()->getIngenicoConfig()->shaOutParameters);
        $model->setSHAOutParametersAliasGateway(\mo_ingenico__main::getInstance()->getIngenicoConfig()->shaOutParametersAliasGateway);
        $model->setSHAOutParametersHostedTokenizationPage(\mo_ingenico__main::getInstance()->getIngenicoConfig()->shaOutParametersHostedTokenization);
        return $model;
    }
}

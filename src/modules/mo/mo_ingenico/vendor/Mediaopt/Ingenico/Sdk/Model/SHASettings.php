<?php

namespace Mediaopt\Ingenico\Sdk\Model;

/**
 * $Id: $
 */
class SHASettings
{
    /**
     *
     * @var string
     */
    private $SHAInKey;

    /**
     *
     * @var string
     */
    private $SHAOutKey;

    /**
     *
     * @var string
     */
    private $SHAAlgorithm;

    /**
     *
     * @var array
     */
    private $SHAOutParameters;

    /**
     *
     * @var array
     */
    private $SHAOutParametersAliasGateway;

    /**
     *
     * @var array
     */
    private $SHAOutParametersHostedTokenizationPage;

    /**
     *
     * @var boolean
     */
    public $isLiveMode;

    public function getIsLiveMode()
    {
        return $this->isLiveMode;
    }

    public function setIsLiveMode($isLiveMode)
    {
        $this->isLiveMode = $isLiveMode;
    }

    public function getSHAInKey()
    {
        return $this->SHAInKey;
    }

    public function getSHAOutKey()
    {
        return $this->SHAOutKey;
    }

    public function getSHAAlgorithm()
    {
        return $this->SHAAlgorithm;
    }

    public function getSHAOutParameters()
    {
        return $this->SHAOutParameters;
    }

    public function setSHAInKey($SHAInKey)
    {
        $this->SHAInKey = $SHAInKey;
    }

    public function setSHAOutKey($SHAOutKey)
    {
        $this->SHAOutKey = $SHAOutKey;
    }

    public function setSHAAlgorithm($SHAAlgorithm)
    {
        $this->SHAAlgorithm = $SHAAlgorithm;
    }

    public function setSHAOutParameters($SHAOutParameters)
    {
        $this->SHAOutParameters = $SHAOutParameters;
    }

    public function getSHAOutParametersAliasGateway()
    {
        return $this->SHAOutParametersAliasGateway;
    }

    public function getSHAOutParametersHostedTokenizationPage()
    {
        return $this->SHAOutParametersHostedTokenizationPage;
    }

    public function setSHAOutParametersAliasGateway($SHAOutParametersAliasGateway)
    {
        $this->SHAOutParametersAliasGateway = $SHAOutParametersAliasGateway;
    }

    public function setSHAOutParametersHostedTokenizationPage($SHAOutParametersHostedTokenizationPage)
    {
        $this->SHAOutParametersHostedTokenizationPage = $SHAOutParametersHostedTokenizationPage;
    }


}

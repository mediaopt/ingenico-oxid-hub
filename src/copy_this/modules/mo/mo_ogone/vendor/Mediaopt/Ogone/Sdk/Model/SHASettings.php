<?php

namespace Mediaopt\Ogone\Sdk\Model;

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
    
    function getSHAInKey()
    {
        return $this->SHAInKey;
    }

    function getSHAOutKey()
    {
        return $this->SHAOutKey;
    }

    function getSHAAlgorithm()
    {
        return $this->SHAAlgorithm;
    }

    function getSHAOutParameters()
    {
        return $this->SHAOutParameters;
    }

    function setSHAInKey($SHAInKey)
    {
        $this->SHAInKey = $SHAInKey;
    }

    function setSHAOutKey($SHAOutKey)
    {
        $this->SHAOutKey = $SHAOutKey;
    }

    function setSHAAlgorithm($SHAAlgorithm)
    {
        $this->SHAAlgorithm = $SHAAlgorithm;
    }

    function setSHAOutParameters($SHAOutParameters)
    {
        $this->SHAOutParameters = $SHAOutParameters;
    }

    function getSHAOutParametersAliasGateway()
    {
        return $this->SHAOutParametersAliasGateway;
    }

    function getSHAOutParametersHostedTokenizationPage()
    {
        return $this->SHAOutParametersHostedTokenizationPage;
    }

    function setSHAOutParametersAliasGateway($SHAOutParametersAliasGateway)
    {
        $this->SHAOutParametersAliasGateway = $SHAOutParametersAliasGateway;
    }

    function setSHAOutParametersHostedTokenizationPage($SHAOutParametersHostedTokenizationPage)
    {
        $this->SHAOutParametersHostedTokenizationPage = $SHAOutParametersHostedTokenizationPage;
    }


}

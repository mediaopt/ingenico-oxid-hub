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


}

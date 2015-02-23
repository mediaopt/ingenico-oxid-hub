<?php

namespace Mediaopt\Ogone\Sdk\Model;

/**
 * $Id: $
 */
class RequestParameters extends AbstractModel
{
    /**
     *
     * @var array
     */
    private $params;
    
    function getParams()
    {
        return $this->params;
    }

    function setParams($params)
    {
        $this->params = $params;
    }


    
}

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

    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = array();
        foreach ($params as $key => $val) {
            $this->params[strtoupper($key)] = $val;
        }
    }


}

<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

/**
 * $Id: $
 */
class RequestParameters extends AbstractFactory
{
        /**
     * populate RequestParameters model
     * @return \Mediaopt\Ogone\Sdk\Model\RequestParameters
     */
    public function build()
    {
        /*@var $model \Mediaopt\Ogone\Sdk\Model\RequestParameters */
        $model = $this->getSdkMain()->getModel('RequestParameters');
        $params = array();
        foreach ($_REQUEST as $val => $key){
            $params[$val] = $this->getOxConfig()->getRequestParameter($val, true);
        }
        $model->setParams($params);
        
        return $model;
    }
}

<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

/**
 * $Id: $
 */
class RequestParameters extends AbstractFactory
{

    /**
     * populate RequestParameters model
     * @param bool $raw Specifies whether the data should be raw or excaped
     * @return \Mediaopt\Ogone\Sdk\Model\RequestParameters
     */
    public function build($raw)
    {
        /* @var $model \Mediaopt\Ogone\Sdk\Model\RequestParameters */
        $model = $this->getSdkMain()->getModel('RequestParameters');
        $params = array();
        foreach ($_REQUEST as $val => $key) {
            $params[$val] = $this->getOxConfig()->getRequestParameter($val, $raw);
        }
        $model->setParams($params);

        return $model;
    }

}

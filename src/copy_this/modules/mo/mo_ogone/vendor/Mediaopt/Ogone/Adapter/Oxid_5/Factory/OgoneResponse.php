<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class OgoneResponse extends AbstractFactory
{

    /**
     * populate OgoneResponse model
     * @param bool $raw Specifies whether the data should be raw or escaped
     * @return Mediaopt\Ogone\Sdk\Model\OgoneResponse
     */
    public function build($raw = false)
    {
        /* @var $model Mediaopt\Ogone\Sdk\Model\OgoneResponse */
        $model = $this->getSdkMain()->getModel('OgoneResponse');
        $params = array();
        foreach ($_REQUEST as $val => $key) {
            $params[$val] = $this->getOxConfig()->getRequestParameter($val, $raw);
        }
        $model->setAllParams($params);
        if (isset($params['orderID'])) {
            $model->setOrderId($params['orderID']);
        } else {
            $model->setOrderId(null);
        }
        if (isset($params['STATUS'])) {
            $statusCode = $params['STATUS'];
        } else {
            $statusCode = StatusType::INCOMPLETE_OR_INVALID;
        }
        $model->setStatusCode($statusCode);
        $model->setStatusService(Main::getInstance()->getService("Status")->usingStatusCode($statusCode));
        return $model;
    }
    
}

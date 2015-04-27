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
        $params = array();
        foreach ($_REQUEST as $key => $val) {
            $params[strtoupper($key)] = $this->getOxConfig()->getRequestParameter($key, $raw);
        }
        return $this->buildFromData($params);
    }
    
    /**
     * populate OgoneResponse model
     * @param type $params a params array
     * @return Mediaopt\Ogone\Sdk\Model\OgoneResponse
     */
    public function buildFromData($params)
    {
        /* @var $model Mediaopt\Ogone\Sdk\Model\OgoneResponse */
        $model = $this->getSdkMain()->getModel('OgoneResponse');
        $model->setAllParams($params);
        if (isset($params['ORDERID'])) {
            $model->setOrderId($params['ORDERID']);
        } else {
            $model->setOrderId(null);
        }
        if (isset($params['STATUS'])) {
            $statusCode = $params['STATUS'];
        } else {
            $statusCode = StatusType::INCOMPLETE_OR_INVALID;
        }
        $model->setStatus(Main::getInstance()->getService("Status")->usingStatusCode($statusCode));
        $error = null;
        if (!empty($params['NCERRORCARDNO']) && $params['NCERRORCARDNO'] != '0') {
            $error = $params['NCERRORCARDNO'];
        } elseif (!empty($params['NCERRORCVC']) && $params['NCERRORCVC'] != '0') {
            $error = $params['NCERRORCVC'];
        } elseif (!empty($params['NCERRORED']) && $params['NCERRORED'] != '0') {
            $error = $params['NCERRORED'];
        } elseif (!empty($params['NCERROR']) && $params['NCERROR'] != '0') {
            $error = $params['NCERROR'];
        }
        if ($error !== null) {
            $model->setError(Main::getInstance()->getService("Status")->usingStatusCode($error));
        } else {
            $model->setError(null);
        }
        if (!empty($params['ALIAS'])) {
            $model->setAlias($params['ALIAS']);
        } else {
            $model->setAlias(null);
        }
        if (!empty($params['AMOUNT'])) {
            $model->setAmount(floatval($params['AMOUNT'])*100);
        } elseif (!empty($params['amount'])) {
            $model->setAmount(floatval($params['amount'])*100);
        } else {
            $model->setAmount(null);
        }
        return $model;
    }
}

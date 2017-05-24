<?php

namespace Mediaopt\Ingenico\Adapter\Oxid_5\Factory;

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class IngenicoResponse extends AbstractFactory
{

    /**
     * populate IngenicoResponse model
     *
     * @param bool $raw Specifies whether the data should be raw or escaped
     * @return \Mediaopt\Ingenico\Sdk\Model\IngenicoResponse
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
     * populate IngenicoResponse model
     *
     * @param array $params a params array
     * @return \Mediaopt\Ingenico\Sdk\Model\IngenicoResponse
     */
    public function buildFromData($params)
    {
        /* @var $model \Mediaopt\Ingenico\Sdk\Model\IngenicoResponse */
        $model = $this->getSdkMain()->getModel('IngenicoResponse');
        $model->setAllParams($params);
        if (isset($params['ORDERID'])) {
            $model->setOrderId($params['ORDERID']);
        } else {
            $model->setOrderId(null);
        }
        if (isset($params['SHOPID'])) {
            $model->setShopId((int)$params['SHOPID']);
        } else {
            $model->setShopId(null);
        }
        if (isset($params['STATUS'])) {
            $statusCode = $params['STATUS'];
        } else {
            $statusCode = StatusType::INCOMPLETE_OR_INVALID;
        }
        $model->setStatus(Main::getInstance()->getService('Status')->usingStatusCode($statusCode));
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
            $model->setError(Main::getInstance()->getService('Status')->usingStatusCode($error));
        } else {
            $model->setError(null);
        }
        if (!empty($params['NCERRORPLUS'])) {
            $model->setErrorPlus($params['NCERRORPLUS']);
        }
        if (!empty($params['ALIAS'])) {
            $model->setAlias($params['ALIAS']);
        } else {
            $model->setAlias(null);
        }
        if (!empty($params['PAYID'])) {
            $model->setPayId($params['PAYID']);
        } else {
            $model->setPayId(null);
        }
        if (!empty($params['SESS_CHALLENGE'])) {
            $model->setSessionChallenge($params['SESS_CHALLENGE']);
        } else {
            $model->setSessionChallenge(null);
        }
        if (!empty($params['AMOUNT'])) {
            $model->setAmount((int)(string)((float)$params['AMOUNT']*100));
        } else {
            $model->setAmount(null);
        }

        return $model;
    }
}

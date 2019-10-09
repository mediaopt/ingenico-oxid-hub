<?php

namespace Mediaopt\Ingenico\Adapter\Oxid_5\Factory;

use Mediaopt\Ingenico\Sdk\Main;

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
        foreach ($params as $paramKey => $value) {
            if (strpos($paramKey, 'ALIAS_') === 0 && !isset($params[substr($paramKey, 6)])) {
                $params[substr($paramKey, 6)] = $value;
            }
        }
        if (isset($params['ORDERID'])) {
            $model->setOrderId($params['ORDERID']);
        }
        if (isset($params['SHOPID'])) {
            $model->setShopId((int)$params['SHOPID']);
        }
        if (isset($params['STATUS'])) {
            $model->setStatus(Main::getInstance()->getService('Status')->usingStatusCode($params['STATUS']));
        }
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
        } elseif (!empty($params['ALIASID'])) {
            $model->setAlias($params['ALIASID']);
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

<?php

namespace Mediaopt\Ogone\Sdk\Model;

use Mediaopt\Ogone\Sdk\Service\Status;

/**
 * $Id: $
 */
class OgoneResponse extends AbstractModel
{

    /**
     *
     * @var array
     */
    private $allParams;
    private $status;
    private $error;
    private $alias;
    private $orderId;
    private $amount;
    private $payId;
    private $shopId;
    private $sessionChallenge;

    /**
     * @return mixed
     */
    public function getSessionChallenge()
    {
        return $this->sessionChellange;
    }

    /**
     * @param mixed $sessionChallenge
     */
    public function setSessionChallenge($sessionChallenge)
    {
        $this->sessionChallenge = $sessionChallenge;
    }

    function getShopId() {
        return $this->shopId;
    }

    function setShopId($shopId) {
        $this->shopId = $shopId;
    }

    function getAmount()
    {
        return $this->amount;
    }
    
    function getPayId()
    {
        return $this->payId;
    }
    
    function setPayId($payId)
    {
        $this->payId = $payId;
    }
    
    function setAmount($amount)
    {
        $this->amount = $amount;
    }
    
    function getAllParams()
    {
        return $this->allParams;
    }

    function getStatusCode()
    {
        return $this->statusCode;
    }

    function getOrderId()
    {
        return $this->orderId;
    }

    function setAllParams($allParams)
    {
        $this->allParams = $allParams;
    }

    function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    function getStatus()
    {
        return $this->status;
    }

    function setStatus(Status $status)
    {
        $this->status = $status;
    }

    function getError()
    {
        return $this->error;
    }

    function setError($error)
    {
        $this->error = $error;
    }

    function hasError()
    {
        return $this->error !== null;
    }

    function getAlias()
    {
        return $this->alias;
    }

    function setAlias($alias)
    {
        $this->alias = $alias;
    }

}

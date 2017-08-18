<?php

namespace Mediaopt\Ingenico\Sdk\Model;

use Mediaopt\Ingenico\Sdk\Service\Status;

/**
 * $Id: $
 */
class IngenicoResponse extends AbstractModel
{

    /**
     *
     * @var array
     */
    private $allParams;
    private $status;
    private $error;
    private $errorPlus;
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

    public function getShopId()
    {
        return $this->shopId;
    }

    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getPayId()
    {
        return $this->payId;
    }

    public function setPayId($payId)
    {
        $this->payId = $payId;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAllParams()
    {
        return $this->allParams;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setAllParams($allParams)
    {
        $this->allParams = $allParams;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(Status $status)
    {
        $this->status = $status;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function getErrorPlus()
    {
        return $this->errorPlus;
    }

    public function setErrorPlus($errorPlus)
    {
        $this->errorPlus = $errorPlus;
    }

    public function hasError()
    {
        return $this->error !== null;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

}

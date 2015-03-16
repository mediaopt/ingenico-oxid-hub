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

    private $statusCode;
    
    /**
     *
     * @var Status
     */
    private $statusService;

    private $orderId;

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
    function getStatusService()
    {
        return $this->statusService;
    }

    function setStatusService(Status $statusService)
    {
        $this->statusService = $statusService;
    }


}

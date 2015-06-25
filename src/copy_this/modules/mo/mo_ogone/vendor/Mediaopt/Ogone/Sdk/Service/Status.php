<?php

/**
 * $Id: Status.php 6 2015-02-24 14:23:57Z mbe $ 
 */

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Model\StatusType;

class Status extends AbstractService
{

    protected $code;

    public function usingStatusCode($code)
    {
        $this->code = $code;
        return $this;
    }

    function getStatusCode()
    {
        return $this->code;
    }

    function setStatusCode($code)
    {
        $this->code = $code;
    }

    public function getStatusTextForCode()
    {
        $statusTextArray = StatusType::getStatusTextByCode();
        return $statusTextArray[$this->code];
    }

    public function isCancelledByClient()
    {
        return $this->code === StatusType::CANCELLED_BY_CLIENT;
    }

    public function isIncomleteOrInvalid()
    {
        return $this->code === StatusType::INCOMPLETE_OR_INVALID;
    }

    public function isShaInMismatch()
    {
        return $this->code === StatusType::SHA_IN_MISMATCH;
    }

    /**
     * @param $code int status code
     * @return bool true if the user completed the order successfully
     */
    public function isThankyouStatus()
    {
        /* @var $model StatusType */
        $model = $this->getAdapter()->getFactory("StatusType")->build($this->code);
        return $model->getIsThankyouStatus();
    }

    /**
     * @param $code int status code
     * @return bool true if order status (oxorder__oxtransstatus) can set to OK
     */
    public function isOkStatus()
    {
        /* @var $model StatusType */
        $model = $this->getAdapter()->getFactory("StatusType")->build($this->code);
        return $model->getIsOkStatus();
    }

    /**
     * @param $code int status code
     * @return string translated status message
     */
    public function getTranslatedStatusMessage()
    {
        /* @var $model StatusType */
        $model = $this->getAdapter()->getFactory("StatusType")->build($this->code);
        return $model->getTranslatedStatusMessage();
    }

    /**
     * @param $code int status code
     * @return bool true if order should be cancelled (storno)
     */
    public function isStornoStatus()
    {
        if ($this->code == StatusType::CANCELLED_BY_CLIENT) {
            return true;
        }
        return false;
    }

}

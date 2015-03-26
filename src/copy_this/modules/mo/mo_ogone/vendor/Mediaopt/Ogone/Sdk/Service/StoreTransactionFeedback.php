<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;


class StoreTransactionFeedback extends AbstractService
{
    public function store($requestParams, $orderid = "") {
        $this->getAdapter()->getUtils()->storeTransactionFeedbackInDb($requestParams, $orderid);
    }
}

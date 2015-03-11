<?php

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\StatusType;
use Mediaopt\Ogone\Sdk\Service\Status;

/**
 * $Id: mo_ogone__feedback_handler.php 53 2014-11-28 13:54:26Z martin $ 
 */
class mo_ogone__feedback_handler
{

    public function __construct($logger, $oxUtilsObject)
    {
        $this->logger = $logger;
        $this->oxUtilsObject = $oxUtilsObject;
    }

    /**
     * @return void
     */
    public function processDeferredFeedback($requestParams)
    {
        $this->logger->logExecution($requestParams);

        if (!Main::getInstance()->getService("Authenticator")->authenticateRequest()) {
            return;
        }

        if (!$this->checkRequiredParams($requestParams, array('orderID', 'STATUS'))) {
            $this->logger->error("Could not update order status, because of missing request params!");
            return;
        }

        $order = oxNew('oxorder');
        $order->mo_ogone__loadByNumber($requestParams['orderID']);
        if (!$order->isLoaded()) {
            $this->logger->error("Could not load order: " . $requestParams['orderID']);
            return;
        }

        $order->mo_ogone__updateOrderStatus($requestParams['STATUS']);

        mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $requestParams);
    }

    protected function checkRequiredParams($params, $requiredParams)
    {
        $result = true;
        foreach ($requiredParams as $requiredParam) {
            if (!isset($params[$requiredParam])) {
                $this->logger->error("Missing request parameter: '$requiredParam'!");
                $result = false;
            }
        }
        return $result;
    }

}

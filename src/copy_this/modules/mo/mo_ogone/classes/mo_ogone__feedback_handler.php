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

    // contains no sha-return because of server-to-server communication
    public function processDirectLinkFeedback($data)
    {
        $this->logger->logExecution($data);

        //no response: curl timeout etc... we assume an uncertain status, redirect to error view
        if (count($data) === 0) {
            $this->logger->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
            $redirectUrl = oxRegistry::getConfig()->getSslShopUrl() . 'index.php?cl=mo_ogone__order_error';
            return oxRegistry::getUtils()->redirect($redirectUrl);
        }

        /* @var $status Status */
        $status = Main::getInstance()->getService("Status");

        //check for error-code
        if (!isset($data['STATUS'])) {
            $status->setStatusCode(StatusType::INCOMPLETE_OR_INVALID);
            $this->logger->error('DirectLink-Response contains no STATUS-Property!');
        } else {
            $status->setStatusCode($data['STATUS']);
            $this->logger->info('GOT Ogone-Status in Direct-Link-Response: ' . $status->getStatusCode());
        }

        //3D-Secure 
        if ($xml->HTML_ANSWER) {
            $this->logger->info('Ogone 3D-Secure redirection (' . $status->getStatusCode() . ')');
            echo '<html><body>';
            echo base64_decode((string) $xml->HTML_ANSWER);
            echo '</body></html>';
            exit;
        }

        //translate status into OXID world
        if ($status->isThankyouStatus()) {
            $this->logger->info('Ogone DirectLink Success (' . $status->getStatusCode() . ')');
            return oxOrder::ORDER_STATE_OK;
        }

        //return error-message => user will be redirected to payment view

        $errorMessage = $status->getTranslatedStatusMessage();
        if ($data['NCERROR']) {
            $errorStatus = Main::getInstance()->getService("Status")->usingStatusCode($data['NCERROR']);
            $errorMessage = $errorStatus->getTranslatedStatusMessage();
        }
        $this->logger->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);
        return $errorMessage;
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

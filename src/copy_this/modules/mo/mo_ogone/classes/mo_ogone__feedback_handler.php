<?php

use Mediaopt\Ogone\Sdk\Main;

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
     * Process 
     *  - redirect feedback and
     *  - 3DSecure Feedback
     */
    public function processOrderFeedback($order)
    {
        $this->logger->logExecution($_REQUEST);

        if (!Main::getInstance()->getService("Authenticator")->authenticateRequest()) {
            // no authentication, kick back to payment methods
            return oxOrder::ORDER_STATE_PAYMENTERROR;
        }

        // handles redirections from the payment server back to the store
        /* @var Mediaopt\Ogone\Sdk\Service\Status $status */
        $status = Main::getInstance()->getService("Status")
                ->usingStatusCode((int) oxRegistry::getConfig()->getRequestParameter('STATUS'));
        $statusDebugInfo = 'Ogone-Status: ' .
                $status->getStatusTextForCode() . ' (' . $status->getStatusCode() . ')';

        $order->mo_ogone__updateOrderStatus($status->getStatusCode());
        mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $_REQUEST, $order);

        if ($status->isThankyouStatus()) {
            $this->logger->info('Ogone Transaction Success - ' . $statusDebugInfo);

            return oxOrder::ORDER_STATE_OK;
        }

        $errorMessage = $status->getTranslatedStatusMessage();
        if ($_REQUEST['NCERROR']) {
            $errrorStatus = Main::getInstance()->getService("Status")->usingStatusCode($_REQUEST['NCERROR']);
            $errorMessage = $errrorStatus->getTranslatedStatusMessage();
        }

        $this->logger->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);

        if ($status->isCancelledByClient()) {
            $this->logger->info("Cancelled by client, force create new order");

            // force create new order, this is at least necessary if the user cancels the payment on the ogone
            // page, than we need to create a new order, with a new order id.
            // reset session order id, so a new order is created, old order keeps in database
            // (ogone does not accept an order with an order-id which was formally canceled)
            oxRegistry::getSession()->setVariable('sess_challenge', $this->oxUtilsObject->generateUID()); // <-- forces new order creation
        }

        return $errorMessage;
    }

    // contains no sha-return because of server-to-server communication
    public function processDirectLinkFeedback($response, $order)
    {
        $this->logger->logExecution($response);

        //no response: curl timeout etc... we assume an uncertain status, redirect to error view
        if (!$response) {
            $this->logger->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
            $redirectUrl = oxRegistry::getConfig()->getSslShopUrl() . 'index.php?cl=mo_ogone__order_error&order_id=' . $order->oxorder__oxordernr->value;
            return oxRegistry::getUtils()->redirect($redirectUrl);
            exit;
        }

        $data = array();
        $xml = simplexml_load_string($response);
        //convert to array
        foreach ($xml->attributes() as $key => $value) {
            $data[(string) $key] = (string) $value;
        }

        /* @var Mediaopt\Ogone\Sdk\Service\Status $status */
        $status = Main::getInstance()->getService("Status");

        //check for error-code
        if (!isset($data['STATUS'])) {
            $status->setStatusCode(Mediaopt\Ogone\Sdk\Model\StatusType::INCOMPLETE_OR_INVALID);
            $this->logger->error('DirectLink-Response contains no STATUS-Property!');
        } else {
            $status->setStatusCode($data['STATUS']);
            $this->logger->info('GOT Ogone-Status in Direct-Link-Response: ' . $status->getStatusCode());
        }

        $order->mo_ogone__updateOrderStatus($status->getStatusCode());

        //logging
        mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $data, $order);

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

        mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $requestParams, $order);
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

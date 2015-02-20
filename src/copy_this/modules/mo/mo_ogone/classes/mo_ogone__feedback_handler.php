<?php

/**
 * $Id: mo_ogone__feedback_handler.php 53 2014-11-28 13:54:26Z martin $ 
 */
class mo_ogone__feedback_handler
{

  public function __construct($logger, $authenticator, $oxUtilsObject)
  {
    $this->logger = $logger;
    $this->authenticator = $authenticator;
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

    if (!$this->authenticator->authenticateRequest())
    {
      // no authentication, kick back to payment methods
      return oxOrder::ORDER_STATE_PAYMENTERROR;
    }

    // handles redirections from the payment server back to the store
    $ogoneStatus = (int) oxRegistry::getConfig()->getRequestParameter('STATUS');

    $statusDebugInfo =
            'Ogone-Status: ' .
            mo_ogone__status::getStatusTextForCode($ogoneStatus) . ' (' . $ogoneStatus . ')';

    $order->mo_ogone__updateOrderStatus($ogoneStatus);
    mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $_REQUEST, $order);

    if (mo_ogone__status::isOxidThankyouStatus($ogoneStatus))
    {
      $this->logger->info('Ogone Transaction Success - ' . $statusDebugInfo);

      return oxOrder::ORDER_STATE_OK;
    }

    $errorMessage = mo_ogone__status::getTranslatedStatusMessage($ogoneStatus);
    if ($_REQUEST['NCERROR'])
    {
      $errorMessage = mo_ogone__status::getTranslatedStatusMessage($_REQUEST['NCERROR']);
    }

    $this->logger->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);

    if ($ogoneStatus == mo_ogone__status::CANCELLED_BY_CLIENT)
    {
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
    if (!$response)
    {
      $this->logger->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
      $redirectUrl = oxRegistry::getConfig()->getSslShopUrl() . 'index.php?cl=mo_ogone__order_error&order_id=' . $order->oxorder__oxordernr->value;
      return oxRegistry::getUtils()->redirect($redirectUrl);
      exit;
    }

    $data = array();
    $xml = simplexml_load_string($response);
    //convert to array
    foreach ($xml->attributes() as $key => $value)
    {
      $data[(string) $key] = (string) $value;
    }


    //check for error-code
    $ogoneStatus = $data['STATUS'];
    if (!isset($data['STATUS']))
    {
      $this->logger->error('DirectLink-Response contains no STATUS-Property!');
      $ogoneStatus = mo_ogone__status::INCOMPLETE_OR_INVALID;
    }
    else
    {
      $this->logger->info('GOT Ogone-Status in Direct-Link-Response: ' . $ogoneStatus);
    }

    $order->mo_ogone__updateOrderStatus($ogoneStatus);

    //logging
    mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $data, $order);

    //3D-Secure 
    if ($xml->HTML_ANSWER)
    {
      $this->logger->info('Ogone 3D-Secure redirection (' . $ogoneStatus . ')');
      echo '<html><body>';
      echo base64_decode((string) $xml->HTML_ANSWER);
      echo '</body></html>';
      exit;
    }

    //translate status into OXID world
    if (mo_ogone__status::isOxidThankyouStatus($ogoneStatus))
    {
      $this->logger->info('Ogone DirectLink Success (' . $ogoneStatus . ')');
      return oxOrder::ORDER_STATE_OK;
    }

    //return error-message => user will be redirected to payment view
    $errorMessage = mo_ogone__status::getTranslatedStatusMessage($ogoneStatus);
    if ($data['NCERROR'])
    {
      $errorMessage = mo_ogone__status::getTranslatedStatusMessage($data['NCERROR']);
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

    if (!$this->authenticator->authenticateRequest())
    {
      return;
    }

    if (!$this->checkRequiredParams($requestParams, array('orderID', 'STATUS')))
    {
      $this->logger->error("Could not update order status, because of missing request params!");
      return;
    }

    $order = oxNew('oxorder');
    $order->mo_ogone__loadByNumber($requestParams['orderID']);
    if (!$order->isLoaded())
    {
      $this->logger->error("Could not load order: " . $requestParams['orderID']);
      return;
    }

    $order->mo_ogone__updateOrderStatus($requestParams['STATUS']);

    mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $requestParams, $order);
  }

  protected function checkRequiredParams($params, $requiredParams)
  {
    $result = true;
    foreach ($requiredParams as $requiredParam)
    {
      if (!isset($params[$requiredParam]))
      {
        $this->logger->error("Missing request parameter: '$requiredParam'!");
        $result = false;
      }
    }
    return $result;
  }

}
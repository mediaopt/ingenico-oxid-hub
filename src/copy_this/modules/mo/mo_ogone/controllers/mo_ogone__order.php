<?php

use Mediaopt\Ogone\Sdk\Main;

/**
 * This file is part of Ogone Payment Solutions payment interface
 *
 * Ogone Payment Solutions payment interface is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ogone Payment Solutions payment interface is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ogone Payment Solutions payment interface.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @link http://www.ogone.com
 * @package modules
 * @copyright (C) Ogone 2009, 2010
 * @version 1.3
 * $Id: mo_ogone__order.php 56 2014-12-01 10:36:13Z martin $
 */
class mo_ogone__order extends mo_ogone__order_parent
{

  /**
   * Handles: 
   *  - redirect feedback (redirect-payment-methods)
   *  - 3DS-Feedback for: ACCEPTURL, DECLINEURL
   */
  public function mo_ogone__fncHandleOgoneRedirect()
  {
    // prevent basketitem isBuyable validation
    // use of basket between order and thankyou views causes errors, when buying last item in stock
    oxRegistry::getConfig()->setConfigParam('mo_ogone__prevent_recalculate', true);
    oxRegistry::getSession()->getBasket()->afterUpdate();

    $order = oxNew('oxorder');
    $order->load($_REQUEST['sess_challenge']);

    $orderState = mo_ogone__main::getInstance()->getFeedbackHandler()->processOrderFeedback($order);

    $orderState = $this->mo_ogone__getOrderStateWithMailError($orderState);

    return parent::_getNextStep($orderState);
  }

  /**
   * 3DS-Feedback: EXCEPTIONURL
   * redirect to error view
   */
  public function mo_ogone__fncHandleOgoneExceptionFeedbackFrom3dSecureRequest()
  {
    mo_ogone__main::getInstance()->getLogger()->logExecution();

    //set ogone Status
    $order = oxNew('oxorder');
    $order->load(oxRegistry::getSession()->getVariable('sess_challenge'));

    $ogoneStatus = $_REQUEST['STATUS'];

    $order->mo_ogone__updateOrderStatus($ogoneStatus);

    mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $_REQUEST, $order);

    $redirectUrl = $this->getConfig()->getSslShopUrl() . 'index.php?cl=mo_ogone__order_error&order_id=' . $order->oxorder__oxordernr->value;
    return oxRegistry::getUtils()->redirect($redirectUrl);
    exit;
  }

  /**
   * @overwritten
   */
  protected function _getNextStep($orderState)
  {
    $order = oxNew('oxorder');
    $order->load(oxRegistry::getSession()->getVariable('sess_challenge'));

    if (!$order->isLoaded())
    {
      return parent::_getNextStep($orderState);
    }

    if (!$order->mo_ogone__isOgoneOrder())
    {
      return parent::_getNextStep($orderState);
    }

    //oxOrder::ORDER_STATE_ORDEREXISTS == "reload-blocker"
    if ($orderState === oxOrder::ORDER_STATE_ORDEREXISTS)
    {
      return parent::_getNextStep($orderState);
    }

    if ($orderState === oxOrder::ORDER_STATE_MAILINGERROR)
    {
      oxRegistry::getSession()->setVariable('mo_ogone__mailError', true);
    }

    //check for one page checkout => call orderdirect-service
    $paymentId   = $this->getPayment()->getId();
    $paymentType =
            mo_ogone__main::getInstance()->getOgoneConfig()->getPaymentMethodProperty($paymentId, 'paymenttype');

    //one page
    if ($paymentType === MO_OGONE__PAYMENTTYPE_ONE_PAGE)
    {
      $order->mo_ogone__initBeforePayment();

      if ($orderState === oxOrder::ORDER_STATE_OK ||
              $orderState === oxOrder::ORDER_STATE_MAILINGERROR ||
              ($orderState === oxOrder::ORDER_STATE_ORDEREXISTS && !$order->mo_ogone__isPaymentDone()))
      {
        // server to server communication
        $response   = $this->mo_ogone__callOrderDirectGateway($order);
        $orderState = mo_ogone__main::getInstance()->getFeedbackHandler()->processDirectLinkFeedback($response, $order);
        $orderState = $this->mo_ogone__getOrderStateWithMailError($orderState);
        return parent::_getNextStep($orderState);
      }
    }

    if ($paymentType === MO_OGONE__PAYMENTTYPE_REDIRECT)
    {
      if ($orderState === oxOrder::ORDER_STATE_OK || $orderState === oxOrder::ORDER_STATE_MAILINGERROR)
      {
        // order is ready -> forward to payment page
        $order->mo_ogone__initBeforePayment();
        return 'mo_ogone__payment_form';
      }
      if (!$order->mo_ogone__isPaymentDone())
      {
        return 'mo_ogone__payment_form';
      }
    }

    return parent::_getNextStep($orderState);
  }

  protected function mo_ogone__callOrderDirectGateway(oxOrder $order)
  {
    if (!function_exists('curl_init'))
    {
      $message = 'curl is missing. Please install php curl extension.';
      mo_ogone__main::getInstance()->getLogger()->error($message);
      return false;
    }

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL            => oxRegistry::getConfig()->getShopConfVar('mo_ogone__gateway_url_orderdir'),
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query(Main::getInstance()->getService("RequestParamBuilder")->buildOnePageOrderdirectParams($order)),
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_TIMEOUT        => mo_ogone__main::getInstance()->getOgoneConfig()->curl_timeout,
    ));

    $result = curl_exec($ch);

    // check for curl error
    if ($result === false)
    {
      // curl error
      $errorNumber  = curl_errno($ch);
      $errorMessage = curl_error($ch);

      mo_ogone__main::getInstance()->getLogger()->error("Curl error: $errorMessage ($errorNumber)");
    }

    curl_close($ch);

    return $result;
  }

  protected function mo_ogone__getOrderStateWithMailError($orderState)
  {
    if ($orderState == oxOrder::ORDER_STATE_OK)
    {
      // check for mail error in session
      if (oxRegistry::getSession()->getVariable('mo_ogone__mailError'))
      {
        $orderState = oxOrder::ORDER_STATE_MAILINGERROR;
        oxRegistry::getSession()->deleteVariable('mo_ogone__mailError');
      }
    }
    return $orderState;
  }

}

<?php

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\StatusType;
use Mediaopt\Ogone\Sdk\Service\Status;

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
     * redirect - if necessary - to payment gateway
     * 
     * @extend execute
     */
    public function execute()
    {
        $oxConfig = $this->getConfig();

        if (!$this->mo_ogone__isOgoneOrder()) {
            return parent::execute();
        }

        if (!$this->_validateTermsAndConditions()) {
            $this->_blConfirmAGBError = 1;

            return;
        }

        /* if ($orderState === oxOrder::ORDER_STATE_MAILINGERROR) {
          oxRegistry::getSession()->setVariable('mo_ogone__mailError', true);
          } */

        //check for one page checkout => call orderdirect-service
        $paymentId = $this->getPayment()->getId();
        $paymentType = mo_ogone__main::getInstance()->getOgoneConfig()->getPaymentMethodProperty($paymentId, 'paymenttype');

        //redirect
        if ($paymentType === MO_OGONE__PAYMENTTYPE_REDIRECT) {
            return "mo_ogone__payment_form";
        }

        //one page
        if ($paymentType === MO_OGONE__PAYMENTTYPE_ONE_PAGE) {
            // server to server communication
            $response = Main::getInstance()->getService("OrderDirectGateway")->call();
            $xml = simplexml_load_string($response);

            /* @var $orderState Status */
            $orderState = Main::getInstance()->getService("OrderDirectGateway")->handleResponse($xml);

            if ($orderState->getStatusCode() === StatusType::INCOMPLETE_OR_INVALID) {
                return parent::_getNextStep($orderState->getTranslatedStatusMessage());
            }
            $data = Main::getInstance()->getService("OrderDirectGateway")->getResponseData($xml);
            $nextStep = $this->mo_ogone__createOrder($orderState, $data);

            //3D-Secure 
            if ($xml->HTML_ANSWER) {
                mo_ogone__main::getInstance()->getLogger()->info('Ogone 3D-Secure redirection');
                echo '<html><body>';
                echo base64_decode((string) $xml->HTML_ANSWER);
                echo '</body></html>';
                exit;
            }
            return $nextStep;
        }
    }

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
        /* @var $orderState Status */
        $orderState = Main::getInstance()->getService("OrderRedirectGateway")->handleResponse();
        $data = Main::getInstance()->getService("OrderRedirectGateway")->getResponseData();
        return $this->mo_ogone__createOrder($orderState, $data);
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

        mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $_REQUEST, "");

        $redirectUrl = $this->getConfig()->getSslShopUrl() . 'index.php?cl=mo_ogone__order_error&order_id='
                . $order->oxorder__oxordernr->value;
        return oxRegistry::getUtils()->redirect($redirectUrl);
        exit;
    }

    protected function mo_ogone__getOrderStateWithMailError($orderState)
    {
        if ($orderState == oxOrder::ORDER_STATE_OK) {
            // check for mail error in session
            if (oxRegistry::getSession()->getVariable('mo_ogone__mailError')) {
                $orderState = oxOrder::ORDER_STATE_MAILINGERROR;
                oxRegistry::getSession()->deleteVariable('mo_ogone__mailError');
            }
        }
        return $orderState;
    }

    /**
     * 
     * @param StatusType $orderState the returned status of Ogone
     * @return type The next step to perform (show error or go to thankyou page)
     */
    protected function mo_ogone__createOrder($orderState, $data)
    {
        if ($orderState->isThankyouStatus()) {
            $parentState = parent::execute();
            // ignore parent Mail error
            if ($parentState === oxOrder::ORDER_STATE_MAILINGERROR) {
                $parentState = oxOrder::ORDER_STATE_OK;
            }
            $parentState = $this->mo_ogone__getOrderStateWithMailError($parentState);
            /* @var $oxOrder oxOrder */
            $oxOrder = oxNew("oxOrder");
            $oxOrder->load($this->getBasket()->getOrderId());
            $oxOrder->mo_ogone__updateOrderStatus($orderState->getStatusCode());
            mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $data, $oxOrder->oxorder__oxordernr->value);
            return $parentState;
        }
        mo_ogone__util::storeTransactionFeedbackInDb(oxDb::getDb(), $data, "");
        return parent::_getNextStep($orderState->getTranslatedStatusMessage());
    }

    /**
     * returns whether current payment is OGONE
     * 
     * @return boolean 
     */
    public function mo_ogone__isOgoneOrder()
    {
        $payment = $this->getPayment();

        if (!$payment) {
            return false;
        }

        return $payment->mo_ogone__isOgonePayment();
    }

}

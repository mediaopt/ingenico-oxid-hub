<?php

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;
use Mediaopt\Ogone\Sdk\Model\StatusType;

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

        if (method_exists(oxNew('oxorder'), '_validateTermsAndConditions') && !$this->_validateTermsAndConditions()) {
                $this->_blConfirmAGBError = 1;
                return;
        } elseif (method_exists(oxConfig, 'getParameter') && !oxConfig::getParameter( 'ord_agb' ) && $this->getConfig()->getConfigParam( 'blConfirmAGB' )) {
            $this->_blConfirmAGBError = 1;
            return;
        }
        
        /* if ($orderState === oxOrder::ORDER_STATE_MAILINGERROR) {
          oxRegistry::getSession()->setVariable('mo_ogone__mailError', true);
          } */

        //check for one page checkout => call orderdirect-service
        $paymentId = $this->getPayment()->getId();
        $paymentType = Main::getInstance()->getService('OgonePayments')->getPaymentMethodProperty($paymentId, 'paymenttype');

        //redirect
        if ($paymentType === MO_OGONE__PAYMENTTYPE_REDIRECT) {
            return "mo_ogone__payment_form";
        }

        //one page
        if ($paymentType === MO_OGONE__PAYMENTTYPE_ONE_PAGE) {
            // server to server communication
            $paramBuilder = oxNew('mo_ogone__order_direct_param_builder');
            $params = $paramBuilder->build();
            $response = Main::getInstance()->getService("OrderDirectGateway")->call($paramBuilder->getUrl(), $params);
            $xml = simplexml_load_string($response);

            /* @var $response OgoneResponse */
            $response = Main::getInstance()->getService("OrderDirectGateway")->handleResponse($xml);

            if ($response->getStatus()->getStatusCode() === StatusType::INCOMPLETE_OR_INVALID) {
                return parent::_getNextStep($response->getStatus()->getTranslatedStatusMessage());
            }
            $nextStep = $this->mo_ogone__createOrder($response);

            //3D-Secure 
            if ($xml->HTML_ANSWER) {
                Main::getInstance()->getLogger()->info('Ogone 3D-Secure redirection for TransId '.$response->getOrderId());
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
        $authenticator = Main::getInstance()->getService("Authenticator");
        $authenticator->setShaSettings(oxNew('mo_ogone__sha_settings')->build());
        /* @var $response OgoneResponse */
        $response = Main::getInstance()->getService("OrderRedirectGateway")->handleResponse($authenticator);
        if ($order = $this->mo_ogone__loadOrder($response)) {
            Main::getInstance()->getLogger()->info("OgoneRedirect: Order already exists. Redirect to thankyou page for TransId ".$response->getOrderId());
            return 'thankyou';
        }
        return $this->mo_ogone__createOrder($response);
    }

    protected function mo_ogone__getOrderStateWithMailError($orderState)
    {
        if ($orderState == 'thankyou') {
            // check for mail error in session
            if (oxRegistry::getSession()->getVariable('mo_ogone__mailError')) {
                $orderState = $this->_getNextStep(oxOrder::ORDER_STATE_MAILINGERROR);
                oxRegistry::getSession()->deleteVariable('mo_ogone__mailError');
            }
        }
        return $orderState;
    }

        protected function mo_ogone__getFormatedOrderAmount()
    {
        $dAmount = oxRegistry::getSession()->getBasket()->getPrice()->getBruttoPrice();
        $dAmount = number_format($dAmount, 2, '.', '');
        $dAmount = $dAmount * 100;
        $dAmount = round($dAmount, 0);
        $dAmount = substr($dAmount, 0, 15);
        return $dAmount;
    }
    
    /**
     * 
     * @param OgoneResponse $response the response of Ogone
     * @return type The next step to perform (show error or go to thankyou page)
     */
    protected function mo_ogone__createOrder($response)
    {
        if (!$response->hasError() && $response->getStatus()->isThankyouStatus()) {
            $basketAmount = $this->mo_ogone__getFormatedOrderAmount();
            if (intval($basketAmount) !== intval($response->getAmount())) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay(oxRegistry::getLang()->translateString('MO_OGONE__DIVERGENT_AMOUNT') . $response->getOrderId());
                Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams(), "");
                Main::getInstance()->getLogger()->error('Paid Amount ('. intval($response->getAmount()) .') and Basket Amount ('.intval($basketAmount) .') are not equal. TransId: '.$response->getOrderId());
                return 'payment';
            }
            if ($response->getShopId() !== NULL && $response->getShopId() !== intval(oxRegistry::getConfig()->getShopId())) {
                Main::getInstance()->getLogger()->info('Changing shopId from '.intval(oxRegistry::getConfig()->getShopId())." to ".$response->getShopId(). " for Order Creation of TransId ".$response->getOrderId());
                oxRegistry::getConfig()->setShopId($response->getShopId());
            } else {
                Main::getInstance()->getLogger()->info('ShopId is correct ('.oxRegistry::getConfig()->getShopId(). ") for Order Creation of TransId ".$response->getOrderId());
            }
            oxRegistry::getSession()->setVariable('mo_ogone__mailError', true);
            $parentState = parent::execute();
            // ignore parent Mail error
            if ($parentState === oxOrder::ORDER_STATE_MAILINGERROR) {
                $parentState = oxOrder::ORDER_STATE_OK;
            }
            /* @var $oxOrder oxOrder */
            $oxOrder = oxNew("oxOrder");
            $oxOrder->load($this->getBasket()->getOrderId());
            if (!$oxOrder) {
                oxRegistry::get("oxUtilsView")->addErrorToDisplay(oxRegistry::getLang()->translateString('MO_OGONE__ORDER_NOT_CREATED') . $response->getOrderId());
                Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams(), "");
                Main::getInstance()->getLogger()->error('The order could not be created. TransId: '.$response->getOrderId());
                return 'payment';
            }
            $oxOrder->mo_ogone__updateOrderStatus($response->getStatus());
            if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__transid_param') === 'PAYID') {
                $id = $response->getPayId();
            } else {
                $id = $response->getOrderId();
            }
            $oxOrder->mo_ogone__setTransID($id);
            Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams(), $oxOrder->oxorder__oxordernr->value);
            $parentState = $this->mo_ogone__getOrderStateWithMailError($parentState);
            Main::getInstance()->getLogger()->debug("OgoneRedirect: Will now redirect to ".$parentState." for TransId ".$response->getOrderId());
            return $parentState;
        }
        Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams(), "");
        if ($response->hasError()) {
            $errorMessage = $response->getError()->getTranslatedStatusMessage();
        } else {
            $errorMessage = $response->getStatus()->getTranslatedStatusMessage();
        }
        $nextStep = parent::_getNextStep($errorMessage);
        Main::getInstance()->getLogger()->debug("OgoneRedirect: Will now redirect to ".$nextStep." for TransId ".$response->getOrderId());
        return $nextStep;
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

    /**
     * Handles: 
     *  - deferred feedback
     */
    public function mo_ogone__fncHandleDeferredFeedback()
    {
        $authenticator = Main::getInstance()->getService("Authenticator");
        $authenticator->setShaSettings(oxNew('mo_ogone__sha_settings')->build());
        // process deferred feedback
        /* @var $response Mediaopt\Ogone\Sdk\Model\OgoneResponse */
        $response = Main::getInstance()->getService("DeferredFeedback")->handleResponse($authenticator);
        if ($response !== null) {
            if ($order = $this->mo_ogone__loadOrder($response)) {
                Main::getInstance()->getLogger()->info("DeferredFeedback: Order already exists (".$order->getId()."). Updating status");
                Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams(), $order->oxorder__oxordernr->value);
                $order->mo_ogone__updateOrderStatus($response->getStatus());
                exit;
            }
            Main::getInstance()->getLogger()->info("DeferredFeedback: Order does not exist yet (".$response->getOrderId()."). Will wait 15 seconds");
            sleep(15);
            if ($order = $this->mo_ogone__loadOrder($response)) {
                Main::getInstance()->getLogger()->info("DeferredFeedback: Order already exists (".$order->getId()."). Updating status");
                Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams(), $order->oxorder__oxordernr->value);
                $order->mo_ogone__updateOrderStatus($response->getStatus());
            } else {
                Main::getInstance()->getLogger()->warning("DeferredFeedback: Could not load order by OrderId: " . $response->getOrderId()." or PAYID ".$response->getPayId());
                $this->mo_ogone__createOrder($response);
            }
        }
        // offline request from ogone, no further processing needed
        exit;
    }

    /**
     * @param $response Mediaopt\Ogone\Sdk\Model\OgoneResponse
     * @return bool|oxOrder
     */
    protected function mo_ogone__loadOrder($response)
    {
        $order = oxNew('oxorder');
        // load by session challenge since that is the new order id (oxid)
        $order->load($response->getSessionChallenge());
        if ($order->isLoaded()) {
            return $order;
        }
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__transid_param') === 'PAYID') {
            $order->mo_ogone__loadByNumber($response->getPayId());
            if ($order->isLoaded()) {
                return $order;
            }
            $order->mo_ogone__loadByNumber($response->getOrderId());
            if ($order->isLoaded()) {
                return $order;
            }
        } else {
            $order->mo_ogone__loadByNumber($response->getOrderId());
            if ($order->isLoaded()) {
                return $order;
            }
            $order->mo_ogone__loadByNumber($response->getPayId());
            if ($order->isLoaded()) {
                return $order;
            }
        }
        return false;
    }
}

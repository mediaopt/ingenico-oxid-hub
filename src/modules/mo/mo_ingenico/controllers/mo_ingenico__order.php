<?php

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;
use Mediaopt\Ingenico\Sdk\Model\StatusType;

/**
 * This file is part of Ingenico Payment Solutions payment interface
 *
 * Ingenico Payment Solutions payment interface is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ingenico Payment Solutions payment interface is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ingenico Payment Solutions payment interface.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @link http://www.ingenico.com
 * @package modules
 * @copyright (C) Ingenico 2009, 2010
 * @version 1.3
 * $Id: mo_ingenico__order.php 56 2014-12-01 10:36:13Z martin $
 */
class mo_ingenico__order extends mo_ingenico__order_parent
{

    /**
     * redirect - if necessary - to payment gateway
     * @todo refactor config usage
     *
     * @extend execute
     */
    public function execute()
    {
        if (!$this->mo_ingenico__isIngenicoOrder()) {
            return parent::execute();
        }

        if (!$this->_validateTermsAndConditions() && method_exists(oxNew('oxorder'), '_validateTermsAndConditions')) {
                $this->_blConfirmAGBError = 1;
                return;
        } elseif (method_exists('oxConfig', 'getParameter') && !oxConfig::getParameter( 'ord_agb' ) && $this->getConfig()->getConfigParam( 'blConfirmAGB' )) {
            $this->_blConfirmAGBError = 1;
            return;
        }

        //check for one page checkout => call orderdirect-service
        $paymentId = $this->getPayment()->getId();
        $paymentType = Main::getInstance()->getService('IngenicoPayments')->getPaymentMethodProperty($paymentId, 'paymenttype');

        //redirect
        if ($paymentType === MO_INGENICO__PAYMENTTYPE_REDIRECT || !oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_hidden_auth')) {
            return 'mo_ingenico__payment_form';
        } else {
            // one page
            // server to server communication
            $paramBuilder = oxNew('mo_ingenico__order_direct_param_builder');
            $params = $paramBuilder->build();
            $response = Main::getInstance()->getService('DirectGateway')->setType('OrderDirect')->call($paramBuilder->getUrl(), $params);
            $xml = simplexml_load_string($response);

            /* @var $response IngenicoResponse */
            $response = Main::getInstance()->getService('DirectGateway')->setType('OrderDirect')->handleResponse($xml);

            if ($response->getStatus()->getStatusCode() === StatusType::INCOMPLETE_OR_INVALID) {
                return parent::_getNextStep($response->getStatus()->getTranslatedStatusMessage());
            }
            $nextStep = $this->mo_ingenico__createOrder($response);

            //3D-Secure 
            if ($xml->HTML_ANSWER) {
                Main::getInstance()->getLogger()->info('Ingenico 3D-Secure redirection for TransId '.$response->getOrderId());
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
    public function mo_ingenico__fncHandleIngenicoRedirect()
    {
        // prevent basketitem isBuyable validation
        // use of basket between order and thankyou views causes errors, when buying last item in stock
        oxRegistry::getConfig()->setConfigParam('mo_ingenico__prevent_recalculate', true);
        oxRegistry::getSession()->getBasket()->afterUpdate();
        $authenticator = Main::getInstance()->getService('Authenticator');
        $authenticator->setShaSettings(oxNew('mo_ingenico__sha_settings')->build());
        /* @var $response IngenicoResponse */
        $response = Main::getInstance()->getService('OrderRedirectGateway')->handleResponse($authenticator);
        if ($order = $this->mo_ingenico__loadOrder($response)) {
            Main::getInstance()->getLogger()->info('IngenicoRedirect: Order already exists. Redirect to thankyou page for TransId ' .$response->getOrderId());
            return 'thankyou';
        }
        return $this->mo_ingenico__createOrder($response);
    }

        protected function mo_ingenico__getFormatedOrderAmount()
    {
        $dAmount = oxRegistry::getSession()->getBasket()->getPrice()->getBruttoPrice();
        $dAmount = number_format($dAmount, 2, '.', '');
        $dAmount *= 100;
        $dAmount = round($dAmount, 0);
        $dAmount = substr($dAmount, 0, 15);
        return $dAmount;
    }

    /**
     * 
     * @param IngenicoResponse $response the response of Ingenico
     * @return string The next step to perform (show error or go to thankyou page)
     */
    protected function mo_ingenico__createOrder($response)
    {
        if (!$response->hasError() && $response->getStatus()->isThankyouStatus()) {
            if (!$basket = $this->getBasket()) {
                oxRegistry::get('oxUtilsView')->addErrorToDisplay(oxRegistry::getLang()->translateString('MO_INGENICO__ERROR_NO_BASKET'));
                oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), "");
                Main::getInstance()->getLogger()->error('No Basket in session. TransId: '.$response->getOrderId());
                return 'payment';
            }
            $basketAmount = oxNew('mo_ingenico__helper')->getFormattedOrderAmount($basket);
            if ((int)$basketAmount !== (int)$response->getAmount()) {
                oxRegistry::get('oxUtilsView')->addErrorToDisplay(oxRegistry::getLang()->translateString('MO_INGENICO__DIVERGENT_AMOUNT') . $response->getOrderId());
                oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), "");
                Main::getInstance()->getLogger()->error('Paid Amount ('. (int)$response->getAmount() .') and Basket Amount ('.(int)$basketAmount .') are not equal. TransId: '.$response->getOrderId());
                return 'payment';
            }
            //@todo check conditions
            if ($response->getShopId() !== NULL && $response->getShopId() !== (int)oxRegistry::getConfig()->getShopId()) {
                Main::getInstance()->getLogger()->info('Changing shopId from '.(int)oxRegistry::getConfig()->getShopId(). ' to ' .$response->getShopId(). ' for Order Creation of TransId ' .$response->getOrderId());
                oxRegistry::getConfig()->setShopId($response->getShopId());
            } else {
                Main::getInstance()->getLogger()->info('ShopId is correct ('.oxRegistry::getConfig()->getShopId(). ') for Order Creation of TransId ' .$response->getOrderId());
            }
            $parentState = parent::execute();

            /* @var $oxOrder oxOrder */
            $oxOrder = oxNew('oxOrder');
            $oxOrder->load($this->getBasket()->getOrderId());
            if (!$oxOrder->isLoaded()) {
                oxRegistry::get('oxUtilsView')->addErrorToDisplay(oxRegistry::getLang()->translateString('MO_INGENICO__ORDER_NOT_CREATED') . $response->getOrderId());
                oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), "");
                Main::getInstance()->getLogger()->error('The order could not be created. TransId: '.$response->getOrderId());
                return 'payment';
            }
            $oxOrder->mo_ingenico__updateOrderStatus($response->getStatus());
            if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__transid_param') === 'PAYID') {
                $id = $response->getPayId();
            } else {
                $id = $response->getOrderId();
            }
            $oxOrder->mo_ingenico__setTransID($id);
            oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), $oxOrder->oxorder__oxordernr->value);
            Main::getInstance()->getLogger()->debug('IngenicoRedirect: Will now redirect to ' .$parentState. ' for TransId ' .$response->getOrderId());
            return $parentState;
        }
        oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), "");
        if ($response->hasError()) {
            $errorMessage = $response->getError()->getTranslatedStatusMessage();
        } else {
            $errorMessage = $response->getStatus()->getTranslatedStatusMessage();
        }
        $nextStep = parent::_getNextStep($errorMessage);
        Main::getInstance()->getLogger()->debug('IngenicoRedirect: Will now redirect to ' .$nextStep. ' for TransId ' .$response->getOrderId());
        return $nextStep;
    }

    /**
     * returns whether current payment is INGENICO
     * 
     * @return boolean 
     */
    public function mo_ingenico__isIngenicoOrder()
    {
        $payment = $this->getPayment();

        if (!$payment) {
            return false;
        }

        return $payment->mo_ingenico__isIngenicoPayment();
    }

    /**
     * Handles: 
     *  - deferred feedback
     */
    public function mo_ingenico__fncHandleDeferredFeedback()
    {
        $authenticator = Main::getInstance()->getService('Authenticator');
        $authenticator->setShaSettings(oxNew('mo_ingenico__sha_settings')->build());
        // process deferred feedback
        /* @var $response Mediaopt\Ingenico\Sdk\Model\IngenicoResponse */
        $response = Main::getInstance()->getService('DeferredFeedback')->handleResponse($authenticator);
        if ($response !== null) {
            if ($order = $this->mo_ingenico__loadOrder($response)) {
                Main::getInstance()->getLogger()->info('DeferredFeedback: Order already exists (' .$order->getId(). '). Updating status');
                oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), $order->oxorder__oxordernr->value);
                $order->mo_ingenico__updateOrderStatus($response->getStatus());
                exit;
            }
            Main::getInstance()->getLogger()->info('DeferredFeedback: Order does not exist yet (' .$response->getOrderId(). '). Will wait 15 seconds');
            sleep(15);
            if ($order = $this->mo_ingenico__loadOrder($response)) {
                Main::getInstance()->getLogger()->info('DeferredFeedback: Order already exists (' .$order->getId(). '). Updating status');
                oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), $order->oxorder__oxordernr->value);
                $order->mo_ingenico__updateOrderStatus($response->getStatus());
            } else {
                Main::getInstance()->getLogger()->warning('DeferredFeedback: Could not load order by OrderId: ' . $response->getOrderId(). ' or PAYID ' .$response->getPayId());
                $this->mo_ingenico__createOrder($response);
            }
        }
        // offline request from ingenico, no further processing needed
        exit;
    }

    /**
     * @param $response Mediaopt\Ingenico\Sdk\Model\IngenicoResponse
     * @return bool|oxOrder
     */
    protected function mo_ingenico__loadOrder($response)
    {
        $order = oxNew('oxorder');
        // load by session challenge since that is the new order id (oxid)
        $order->load($response->getSessionChallenge());
        if ($order->isLoaded()) {
            return $order;
        }
        if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__transid_param') === 'PAYID') {
            $order->mo_ingenico__loadByNumber($response->getPayId());
            if ($order->isLoaded()) {
                return $order;
            }
            $order->mo_ingenico__loadByNumber($response->getOrderId());
            if ($order->isLoaded()) {
                return $order;
            }
        } else {
            $order->mo_ingenico__loadByNumber($response->getOrderId());
            if ($order->isLoaded()) {
                return $order;
            }
            $order->mo_ingenico__loadByNumber($response->getPayId());
            if ($order->isLoaded()) {
                return $order;
            }
        }
        return false;
    }
}

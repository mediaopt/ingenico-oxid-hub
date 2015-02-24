<?php

use Mediaopt\Ogone\Sdk\Main;

/**
 * $Id: mo_ogone__payment.php 44 2014-02-06 13:20:24Z martin $ 
 */
class mo_ogone__payment extends mo_ogone__payment_parent
{

    protected $mo_ogone__currentPaymentConfig = null;
    protected $mo_ogone__aliasGatewayParamsSet = null;

    /**
     * do not recycle orders
     */
    public function init()
    {
        $order = oxNew('oxorder');
        $orderId = oxRegistry::getSession()->getVariable('sess_challenge');

        if ($order->load($orderId)) {
            $order->cancelOrder();
            oxRegistry::getSession()->setVariable('sess_challenge', oxUtilsObject::getInstance()->generateUID());
        }

        parent::init();
    }

    /**
     * @overload oxid fnc (called from payment page, returns: 'order' on success)
     * If: 
     *  - client has JS disabled and
     *  - is ogone payment and
     *  - is one-page-payment
     * Then: 
     *  - show checkout step 3.5 
     *
     * Redirect fnc for ogone alias gateway: ACCEPTURL, EXCEPTIONURL
     */
    public function validatePayment()
    {
        mo_ogone__main::getInstance()->getLogger()->logExecution($_REQUEST);

        $paymentId = oxRegistry::getConfig()->getRequestParameter('paymentid');
        $parentResult = parent::validatePayment();

        //standard procedure with other payments
        if (!$this->mo_ogone__isOgonePayment($paymentId)) {
            return $parentResult;
        }

        $paymentType = mo_ogone__main::getInstance()->getOgoneConfig()->getPaymentMethodProperty($paymentId, 'paymenttype');

        //standard procedure with redirect payments
        if ($paymentType === MO_OGONE__PAYMENTTYPE_REDIRECT) {
            return $parentResult;
        }

        if ($paymentType === MO_OGONE__PAYMENTTYPE_ONE_PAGE) {
            // check for error
            $error = $this->mo_ogone__handleAliasGatewayErrorResponse();

            // check if we got the alias
            if (!$error && isset($_REQUEST['Alias']) && !empty($_REQUEST['Alias'])) {
                // store alias
                oxRegistry::getSession()->setVariable('mo_ogone__order_alias', $_REQUEST['Alias']);
                return 'order';
            }

            // check if js is disabled
            if (!isset($_REQUEST['PARAMVAR']) || $_REQUEST['PARAMVAR'] != 'JS_ENABLED') {
                //fetch necessary auth params and build sha-signature
                $this->mo_ogone__loadRequestParams($paymentId);
                // javascript is not enabled so we need to display step 3.5
                $this->_sThisTemplate = 'mo_ogone__payment_one_page.tpl';
                return;
            }

            // js is enabled, this meens that this request is triggered via ogone alias gateway redirect url
            return 'payment';
        } else {
            mo_ogone__main::getInstance()->getLogger()->error('Unknown payment type: ' . $paymentType);
        }
    }

    /**
     * template method
     */
    public function mo_ogone__loadRequestParams($paymentId)
    {
        $this->mo_ogone__aliasGatewayParamsSet = Main::getInstance()->getService("RequestParamBuilder")->buildAliasGatewayParams($paymentId);
    }

    /**
     * template method
     */
    public function mo_ogone__getAliasGatewayParamsForJavascriptVersion()
    {
        if (!isset($this->mo_ogone__aliasGatewayParamsSet[0])) {
            return array();
        }
        $params = $this->mo_ogone__aliasGatewayParamsSet[0];
        unset($params['SHASIGN']);
        unset($params['BRAND']);
        return $params;
    }

    /**
     * template method
     */
    public function mo_ogone__getAliasGatewayParamsSet()
    {
        return $this->mo_ogone__aliasGatewayParamsSet;
    }

    public function getAliasGatewayShaSignaturesAsJson()
    {
        $signatures = array();
        foreach ($this->mo_ogone__aliasGatewayParamsSet as $params) {
            $signatures[$params['BRAND']] = $params['SHASIGN'];
        }
        return json_encode($signatures);
    }

    /**
     * check if given paymentid is ogone-payment
     * @param type $paymentId
     * @return type 
     */
    protected function mo_ogone__isOgonePayment($paymentId)
    {
        $paymentConfig = mo_ogone__main::getInstance()->getOgoneConfig()->getOgonePayments();
        return array_key_exists($paymentId, $paymentConfig);
    }

    /**
     * return payment-form action URL
     */
    public function mo_ogone__getPaymentGatewayUrl()
    {
        $paymentId = $this->getCheckedPaymentId();

        if ($this->mo_ogone__isOgonePayment($paymentId)) {
            $paymentType = mo_ogone__main::getInstance()->getOgoneConfig()->getPaymentMethodProperty($paymentId, 'paymenttype');
            switch ($paymentType) {
                case MO_OGONE__PAYMENTTYPE_ONE_PAGE:
                    return oxRegistry::getConfig()->getShopConfVar('mo_ogone__gateway_url_alias');
                default:
                    return $this->getViewConfig()->getSslSelfLink();
            }
        }
    }

    public function mo_ogone__getCurrentPaymentConfig($paymentId = null)
    {
        if ($this->mo_ogone__currentPaymentConfig === null) {
            if (!$paymentId) {
                $paymentId = $this->getCheckedPaymentId();
            }
            $this->mo_ogone__currentPaymentConfig = mo_ogone__main::getInstance()->getOgoneConfig()->
                    getOgonePaymentByOxidPaymentId($paymentId);
        }
        return $this->mo_ogone__currentPaymentConfig;
    }

    public function mo_ogone__handleAliasGatewayErrorResponse()
    {
        $error = '';

        if (!empty($_REQUEST['NCErrorCardNo']) && $_REQUEST['NCErrorCardNo'] != '0') {
            $error = $_REQUEST['NCErrorCardNo'];
        } elseif (!empty($_REQUEST['NCErrorCVC']) && $_REQUEST['NCErrorCVC'] != '0') {
            $error = $_REQUEST['NCErrorCVC'];
        } elseif (!empty($_REQUEST['NCErrorED']) && $_REQUEST['NCErrorED'] != '0') {
            $error = $_REQUEST['NCErrorED'];
        } elseif (!empty($_REQUEST['NCError']) && $_REQUEST['NCError'] != '0') {
            $error = $_REQUEST['NCError'];
        }

        if ($error) {
            if ($error == mo_ogone__status::SHA_IN_MISSMATCH) {
                mo_ogone__main::getInstance()->getLogger()->error('SHA-IN Missmatch');
            }
            oxRegistry::get("oxUtilsView")->addErrorToDisplay(mo_ogone__status::getTranslatedStatusMessage($error));
            return true;
        }
        return false;
    }

    /**
     * check paymentlist for BillPay availability
     * @extend getPaymentList
     * @return oxList 
     */
    public function getPaymentList()
    {
        $list = parent::getPaymentList();

        //run once per view
        if (!$this->mo_ogone__isAlreadyChecked) {
            //check billpay availability
            $list = $this->mo_ogone__checkBillpayAvailability($list);
            $this->_oPaymentList = $list;
            $this->mo_ogone__isAlreadyChecked = true;
        }
        return $list;
    }

    /**
     * check if basket contains vouchers or discounts
     * @param type $list
     * @return type 
     */
    protected function mo_ogone__checkBillpayAvailability($list)
    {
        //billpay active ?
        if (empty($list) || !array_key_exists('ogone_open_invoice_de', $list)) {
            return $list;
        }

        //vouchers
        $oxBasket = $this->getSession()->getBasket();
        if (($charge = $oxBasket->getVoucherDiscount()) && $charge->getBruttoPrice()) {
            unset($list['ogone_open_invoice_de']);
            return $list;
        }

        //discounts
        $amount = 0;
        if ($discounts = $oxBasket->getDiscounts()) {
            foreach ($discounts as $discount) {
                $amount += $discount->dDiscount;
            }
        }

        if ($amount) {
            unset($list['ogone_open_invoice_de']);
            return $list;
        }

        return $list;
    }

}

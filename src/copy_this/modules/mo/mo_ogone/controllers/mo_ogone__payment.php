<?php

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;

/**
 * $Id: mo_ogone__payment.php 44 2014-02-06 13:20:24Z martin $ 
 */
class mo_ogone__payment extends mo_ogone__payment_parent
{

    protected $mo_ogone__currentPaymentConfig = null;
    protected $mo_ogone__aliasGatewayParamsSet = null;

    /**
     * @overload oxid fnc (called from payment page, returns: 'order' on success)
     * If: 
     *  - client has JS disabled and
     *  - is ogone payment and
     *  - is one-page-payment
     * Then: 
     *  - show checkout step 3.5 
     *
     */
    public function validatePayment()
    {
        Main::getInstance()->getLogger()->logExecution($_REQUEST);
        /* @var $oxpayment oxpayment */
        $oxpayment = oxNew("oxpayment");
        $oxpayment->load(oxRegistry::getConfig()->getRequestParameter('paymentid'));
        $parentResult = parent::validatePayment();

        //standard procedure with other payments
        if (!$oxpayment->mo_ogone__isOgonePayment()) {
            return $parentResult;
        }

        $paymentType = Main::getInstance()->getService('OgonePayments')->getPaymentMethodProperty($oxpayment->getId(), 'paymenttype');

        //standard procedure with redirect payments
        if ($paymentType === MO_OGONE__PAYMENTTYPE_REDIRECT) {
            return $parentResult;
        } elseif ($paymentType === MO_OGONE__PAYMENTTYPE_ONE_PAGE) {
            return $this->mo_ogone__handleAliasGatewayResponse();
        } else {
            Main::getInstance()->getLogger()->error('Unknown payment type: ' . $paymentType);
        }
    }

    /**
     * Redirect fnc for hosted tokenization gateway: EXCEPTIONURL
     */
    public function mo_ogone__handleHostedTokenizationErrorResponse()
    {
        // check for error
        /* @var $response OgoneResponse */
        $response = Main::getInstance()->getService("AliasGateway")->handleResponse();
        // error will be displayed
        if ($response->hasError()) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($response->getError()->getTranslatedStatusMessage());
        }
        if ($response->getStatus()->getStatusCode() == 3) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($response->getStatus()->getTranslatedStatusMessage());
            return;
        }
    }

    /**
     * Redirect fnc for ogone alias gateway: ACCEPTURL, EXCEPTIONURL and hosted tokenization gateway: ACCEPTURL
     */
    public function mo_ogone__handleAliasGatewayResponse()
    {
        // check for error
        /* @var $response OgoneResponse */
        $response = Main::getInstance()->getService("AliasGateway")->handleResponse();

        // check if we got the alias
        $alias = $response->getAlias();
        if (!$response->hasError() && $alias !== null) {
            // store alias
            oxRegistry::getSession()->setVariable('mo_ogone__order_alias', $alias);
            return 'order';
        }

        // error will be displayed
        if ($response->hasError()) {
            oxRegistry::get("oxUtilsView")->addErrorToDisplay($response->getError()->getTranslatedStatusMessage());
        }

        // check if js is disabled
        elseif (!isset($_REQUEST['PARAMVAR']) || oxRegistry::getConfig()->getRequestParameter('PARAMVAR') != 'JS_ENABLED') {
            //fetch necessary auth params and build sha-signature
            $this->mo_ogone__loadRequestParams(oxRegistry::getConfig()->getRequestParameter('paymentid'));
            // javascript is not enabled so we need to display step 3.5
            $this->_sThisTemplate = 'mo_ogone__payment_one_page.tpl';
            return;
        }

        return 'payment';
    }

    /**
     * template method
     */
    public function mo_ogone__loadRequestParams($paymentId)
    {
        $aliasService;
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_iframe_for_hidden_auth')) {
            $aliasService = Main::getInstance()->getService("HostedTokenizationGateway");
        } else {
            $aliasService = Main::getInstance()->getService("AliasGateway");
        }
        $this->mo_ogone__aliasGatewayParamsSet = $aliasService->buildParams($paymentId);
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

    public function mo_ogone_getIFrameURLsAsJson($paymentId)
    {
        $url = oxRegistry::getConfig()->getShopConfVar('mo_ogone__gateway_url_hostedtoken') . '?';
        if (!isset($this->mo_ogone__aliasGatewayParamsSet[0])) {
            $this->mo_ogone__loadRequestParams($paymentId);
        }
        $urls = array();
        foreach ($this->mo_ogone__aliasGatewayParamsSet as $params) {
            $urls[$params['CARD.BRAND']] = $url . http_build_query($params);
        }
        return json_encode($urls);
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
     * return payment-form action URL
     */
    public function mo_ogone__getPaymentGatewayUrl()
    {
        /* @var $oxpayment oxpayment */
        $oxpayment = oxNew("oxpayment");
        $oxpayment->load($this->getCheckedPaymentId());

        if ($oxpayment->mo_ogone__isOgonePayment()) {
            $paymentType = Main::getInstance()->getService('OgonePayments')->getPaymentMethodProperty($oxpayment->getId(), 'paymenttype');
            switch ($paymentType) {
                case MO_OGONE__PAYMENTTYPE_ONE_PAGE:
                    if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_iframe_for_hidden_auth')) {
                        return oxRegistry::getConfig()->getShopConfVar('mo_ogone__gateway_url_hostedtoken');
                    } else {
                        return oxRegistry::getConfig()->getShopConfVar('mo_ogone__gateway_url_alias');
                    }
                default:
                    return $this->getViewConfig()->getSslSelfLink();
            }
        }
    }

    public function mo_ogone__getCurrentPaymentConfig($paymentId = null)
    {
        if (!$this->mo_ogone__currentPaymentConfig === null) {
            return $this->mo_ogone__currentPaymentConfig;
        }
        if (!$paymentId) {
            $paymentId = $this->getCheckedPaymentId();
        }
        return $this->mo_ogone__currentPaymentConfig = Main::getInstance()->getService('OgonePayments')->
                getOgonePaymentByShopPaymentId($paymentId);
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

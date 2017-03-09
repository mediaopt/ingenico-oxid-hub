<?php

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;

/**
 * $Id: mo_ogone__payment.php 44 2014-02-06 13:20:24Z martin $
 */
class mo_ogone__payment extends mo_ogone__payment_parent
{

    /**
     * @var array
     */
    protected $mo_ogone__currentPaymentConfig = [];

    /**
     * @var array
     */
    protected $mo_ogone__aliasGatewayParamsSet = [];

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
        $oxpayment = oxNew('oxpayment');
        $oxpayment->load(oxRegistry::getConfig()->getRequestParameter('paymentid'));
        $parentResult = parent::validatePayment();

        //standard procedure with other payments
        if (!$oxpayment->mo_ogone__isOgonePayment()) {
            return $parentResult;
        }

        $paymentType = Main::getInstance()->getService('OgonePayments')->getPaymentMethodProperty($oxpayment->getId(), 'paymenttype');

        //standard procedure with redirect payments
        if ($paymentType === MO_OGONE__PAYMENTTYPE_REDIRECT || !oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_hidden_auth')) {
            return $parentResult;
        } else {
            return $this->mo_ogone__handleHiddenAuthorizationResponse();
        }
    }

    /**
     * Redirect fnc for hosted tokenization gateway: EXCEPTIONURL
     */
    public function mo_ogone__handleHostedTokenizationErrorResponse()
    {
        $authenticator = Main::getInstance()->getService('Authenticator');
        $authenticator->setShaSettings(oxNew('mo_ogone__sha_settings')->build());
        // check for error
        /* @var $response OgoneResponse */
        $response = Main::getInstance()->getService('HostedTokenizationGateway')->handleResponse($authenticator);
        // error will be displayed
        if ($response->hasError()) {
            oxRegistry::get('oxUtilsView')->addErrorToDisplay($response->getError()->getTranslatedStatusMessage());
        } elseif ($response->getStatus()->getStatusCode() == 3 || $response->getStatus()->getStatusCode() == 1) {
            oxRegistry::get('oxUtilsView')->addErrorToDisplay($response->getStatus()->getTranslatedStatusMessage());
        }
        return 'payment';
    }

    /**
     * Redirect fnc for ogone alias gateway: ACCEPTURL, EXCEPTIONURL and hosted tokenization gateway: ACCEPTURL
     */
    public function mo_ogone__handleHiddenAuthorizationResponse()
    {
        $authenticator = Main::getInstance()->getService('Authenticator');
        $authenticator->setShaSettings(oxNew('mo_ogone__sha_settings')->build());
        // check for error
        /* @var $response OgoneResponse */
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_iframe')) {
            $response = Main::getInstance()->getService('HostedTokenizationGateway')->handleResponse($authenticator);
        } else {
            $response = Main::getInstance()->getService('AliasGateway')->handleResponse($authenticator);
        }
        // check if we got the alias
        $alias = $response->getAlias();
        if ($alias !== null && !$response->hasError()) {
            // store alias
            oxRegistry::getSession()->setVariable('mo_ogone__order_alias', $alias);
            return 'order';
        }

        // check if js is disabled for Alias Gateway
        if (!oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_iframe') && !strstr(oxRegistry::getConfig()->getRequestParameter('PARAMVAR'), 'JS_ENABLED')) {
            Main::getInstance()->getLogger()->error('JS is not enabled');
            //fetch necessary auth params and build sha-signature
            $this->mo_ogone__loadRequestParams(oxRegistry::getConfig()->getRequestParameter('paymentid'));
            // javascript is not enabled so we need to display step 3.5
            return $this->getNonJsTemplateName();
        }

        // error will be displayed
        if ($response->hasError()) {
            oxRegistry::get('oxUtilsView')->addErrorToDisplay($response->getError()->getTranslatedStatusMessage());
        }

        return 'payment';
    }

    /**
     * template method
     * @param $paymentId
     */
    public function mo_ogone__loadRequestParams($paymentId)
    {
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_iframe')) {
            $aliasService = oxNew('mo_ogone__hosted_tokenization_param_builder');
        } else {
            $aliasService = oxNew('mo_ogone__alias_param_builder');
        }
        $this->mo_ogone__aliasGatewayParamsSet = $aliasService->build($paymentId)->getParams();
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
        unset($params['SHASIGN'], $params['BRAND']);
        return $params;
    }

    public function mo_ogone_getIFrameURLsAsJson($paymentId)
    {
        $url = $this->getHostedTokenUrl() . '?';
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
        $oxpayment = oxNew('oxpayment');
        $oxpayment->load($this->getCheckedPaymentId());

        if ($oxpayment->mo_ogone__isOgonePayment()) {
            $paymentType = Main::getInstance()->getService('OgonePayments')->getPaymentMethodProperty($oxpayment->getId(), 'paymenttype');
            if ($paymentType == MO_OGONE__PAYMENTTYPE_REDIRECT || !oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_hidden_auth')) {
                return $this->getViewConfig()->getSslSelfLink();
            }
            // one page
            if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_iframe')) {
                return $this->getHostedTokenUrl();
            } else {
                return $this->getAliasUrl();
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
        $options = Main::getInstance()->getService('OgonePayments')->
        getOgonePaymentByShopPaymentId($paymentId);
        if (is_array($options['brand'])) {
            foreach ($options['brand'] as $key => $brand) {
                if (!oxNew('mo_ogone__helper')->mo_ogone__isBrandActive($paymentId, $brand)) {
                    unset($options['brand'][$key]);
                }
            }
        }
        return $this->mo_ogone__currentPaymentConfig = $options;
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
     * @param array $list
     * @return array
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
            /** @var array $discounts */
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

    public function getAliasUrl()
    {
        $part1 = 'test';
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__isLiveMode')) {
            $part1 = 'prod';
        }

        $part2 = '';
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_utf8')) {
            $part2 = '_utf8';
        }

        return 'https://secure.ogone.com/ncol/' . $part1 . '/alias_gateway' . $part2 . '.asp';
    }

    protected function getHostedTokenUrl()
    {
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__isLiveMode')) {
            return 'https://secure.ogone.com/Tokenization/HostedPage';
        }

        return 'https://ogone.test.v-psp.com/Tokenization/HostedPage';
    }

    /**
     * return tpl for payment without Javascript enabled
     *
     * @deprecated  azure theme switch will be removed soon
     * @return string
     */
    protected function getNonJsTemplateName()
    {
        if($this->getViewConfig()->getActiveTheme() === 'azure')
        {
            return 'mo_ogone__payment_one_page.tpl';
        }

        return 'mo_ogone__flow_payment_one_page.tpl';
    }


}

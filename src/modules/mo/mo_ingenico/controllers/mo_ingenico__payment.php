<?php

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;

/**
 * $Id: mo_ingenico__payment.php 44 2014-02-06 13:20:24Z martin $
 */
class mo_ingenico__payment extends mo_ingenico__payment_parent
{

    /**
     * @var array
     */
    protected $mo_ingenico__currentPaymentConfig = [];

    /**
     * @var array
     */
    protected $mo_ingenico__aliasGatewayParamsSet = [];

    /**
     * @overload oxid fnc (called from payment page, returns: 'order' on success)
     * If:
     *  - client has JS disabled and
     *  - is ingenico payment and
     *  - is one-page-payment
     * Then:
     *  - show checkout step 3.5
     *
     */
    public function validatePayment()
    {
        /* @var $oxpayment oxpayment */
        $oxpayment = oxNew('oxpayment');
        $oxpayment->load(oxRegistry::getConfig()->getRequestParameter('paymentid'));
        $parentResult = parent::validatePayment();

        //standard procedure with other payments
        if (!$oxpayment->mo_ingenico__isIngenicoPayment()) {
            return $parentResult;
        }
        Main::getInstance()->getLogger()->info('Validating payment', $_REQUEST);

        $paymentType = Main::getInstance()->getService('IngenicoPayments')->getPaymentMethodProperty($oxpayment->getId(), 'paymenttype');

        if (!$brand = oxRegistry::getConfig()->getRequestParameter('BRAND')) {
            $brand = oxRegistry::getConfig()->getRequestParameter('CARD_BRAND');
        }
        if ($oxpayment->getId() === 'ingenico_credit_card' && strpos($brand, 'alias_') === 0) {
            return $this->mo_ingenico__useSelectedAlias($brand);
        }

        //standard procedure with redirect payments
        if ($paymentType === MO_INGENICO__PAYMENTTYPE_REDIRECT || !oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_hidden_auth')) {
            return $parentResult;
        } else {
            return $this->mo_ingenico__handleHiddenAuthorizationResponse();
        }
    }

    /**
     * Redirect fnc for hosted tokenization gateway: EXCEPTIONURL
     */
    public function mo_ingenico__handleHostedTokenizationErrorResponse()
    {
        $authenticator = Main::getInstance()->getService('Authenticator');
        $authenticator->setShaSettings(oxNew('mo_ingenico__sha_settings')->build());
        // check for error
        /* @var $response IngenicoResponse */
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
     * Redirect fnc for ingenico alias gateway: ACCEPTURL, EXCEPTIONURL and hosted tokenization gateway: ACCEPTURL
     */
    public function mo_ingenico__handleHiddenAuthorizationResponse()
    {
        $authenticator = Main::getInstance()->getService('Authenticator');
        $authenticator->setShaSettings(oxNew('mo_ingenico__sha_settings')->build());
        // check for error
        /* @var $response IngenicoResponse */
        if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_iframe')) {
            $response = Main::getInstance()->getService('HostedTokenizationGateway')->handleResponse($authenticator);
        } else {
            $response = Main::getInstance()->getService('AliasGateway')->handleResponse($authenticator);
        }
        // check if we got the alias
        $alias = $response->getAlias();
        if ($alias !== null && !$response->hasError()) {
            if ($this->getConfig()->getShopConfVar('mo_ingenico__use_alias_manager')) {
                // store alias for future use
                $this->getUser()->mo_ingenico__registerAlias($response->getAllParams());
            }
            // store alias
            oxRegistry::getSession()->setVariable('mo_ingenico__order_alias', $alias);
            return 'order';
        }

        // check if js is disabled for Alias Gateway
        if (!oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_iframe') && !strstr(oxRegistry::getConfig()->getRequestParameter('PARAMVAR'), 'JS_ENABLED')) {
            Main::getInstance()->getLogger()->error('JS is not enabled');
            //fetch necessary auth params and build sha-signature
            $this->mo_ingenico__loadRequestParams(oxRegistry::getConfig()->getRequestParameter('paymentid'));
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
    public function mo_ingenico__loadRequestParams($paymentId)
    {
        if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_iframe')) {
            $aliasService = oxNew('mo_ingenico__hosted_tokenization_param_builder');
        } else {
            $aliasService = oxNew('mo_ingenico__alias_param_builder');
        }
        $this->mo_ingenico__aliasGatewayParamsSet = $aliasService->build($paymentId)->getParams();
    }

    /**
     * template method
     */
    public function mo_ingenico__getAliasGatewayParamsForJavascriptVersion()
    {
        if (!isset($this->mo_ingenico__aliasGatewayParamsSet[0])) {
            return array();
        }
        $params = $this->mo_ingenico__aliasGatewayParamsSet[0];
        unset($params['SHASIGN'], $params['BRAND']);
        return $params;
    }

    public function mo_ingenico_getIFrameURLsAsJson($paymentId)
    {
        $url = $this->getHostedTokenUrl() . '?';
        if (!isset($this->mo_ingenico__aliasGatewayParamsSet[0])) {
            $this->mo_ingenico__loadRequestParams($paymentId);
        }
        $urls = array();
        foreach ($this->mo_ingenico__aliasGatewayParamsSet as $params) {
            $urls[$params['CARD.BRAND']] = $url . http_build_query($params);
        }
        return json_encode($urls);
    }

    /**
     * template method
     */
    public function mo_ingenico__getAliasGatewayParamsSet()
    {
        return $this->mo_ingenico__aliasGatewayParamsSet;
    }

    public function getAliasGatewayShaSignaturesAsJson()
    {
        $signatures = array();
        foreach ($this->mo_ingenico__aliasGatewayParamsSet as $params) {
            $signatures[$params['BRAND']] = $params['SHASIGN'];
        }
        return json_encode($signatures);
    }

    /**
     * return payment-form action URL
     */
    public function mo_ingenico__getPaymentGatewayUrl()
    {
        /* @var $oxpayment oxpayment */
        $oxpayment = oxNew('oxpayment');
        $oxpayment->load($this->getCheckedPaymentId());

        if ($oxpayment->mo_ingenico__isIngenicoPayment()) {
            $paymentType = Main::getInstance()->getService('IngenicoPayments')->getPaymentMethodProperty($oxpayment->getId(), 'paymenttype');
            if ($paymentType == MO_INGENICO__PAYMENTTYPE_REDIRECT || !oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_hidden_auth')) {
                return $this->getViewConfig()->getSslSelfLink();
            }
            // one page
            if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_iframe')) {
                return $this->getHostedTokenUrl();
            } else {
                return $this->getAliasUrl();
            }
        }
    }

    /**
     * @param null|string $paymentId
     * @return array
     */
    public function mo_ingenico__getCurrentPaymentConfig($paymentId = null)
    {
        if (!$this->mo_ingenico__currentPaymentConfig === null) {
            return $this->mo_ingenico__currentPaymentConfig;
        }
        if (!$paymentId) {
            $paymentId = $this->getCheckedPaymentId();
        }
        $options = Main::getInstance()->getService('IngenicoPayments')->
        getIngenicoPaymentByShopPaymentId($paymentId);
        if (is_array($options['brand'])) {
            foreach ($options['brand'] as $key => $brand) {
                if (!oxNew('mo_ingenico__helper')->mo_ingenico__isBrandActive($paymentId, $brand)) {
                    unset($options['brand'][$key]);
                }
            }
        }
        return $this->mo_ingenico__currentPaymentConfig = $options;
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
        if (!$this->mo_ingenico__isAlreadyChecked) {
            //check billpay availability
            $list = $this->mo_ingenico__checkBillpayAvailability($list);
            $this->_oPaymentList = $list;
            $this->mo_ingenico__isAlreadyChecked = true;
        }
        return $list;
    }

    /**
     * check if basket contains vouchers or discounts
     * @param array $list
     * @return array
     */
    protected function mo_ingenico__checkBillpayAvailability($list)
    {
        //billpay active ?
        if (empty($list) || !array_key_exists('ingenico_open_invoice_de', $list)) {
            return $list;
        }

        //vouchers
        $oxBasket = $this->getSession()->getBasket();
        if (($charge = $oxBasket->getVoucherDiscount()) && $charge->getBruttoPrice()) {
            unset($list['ingenico_open_invoice_de']);
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
            unset($list['ingenico_open_invoice_de']);
            return $list;
        }

        return $list;
    }

    public function getAliasUrl()
    {
        $part1 = 'test';
        if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__isLiveMode')) {
            $part1 = 'prod';
        }

        $part2 = '';
        if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_utf8')) {
            $part2 = '_utf8';
        }

        return 'https://secure.ingenico.com/ncol/' . $part1 . '/alias_gateway' . $part2 . '.asp';
    }

    protected function getHostedTokenUrl()
    {
        if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__isLiveMode')) {
            return 'https://secure.ingenico.com/Tokenization/HostedPage';
        }

        return 'https://ingenico.test.v-psp.com/Tokenization/HostedPage';
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
            return 'mo_ingenico__payment_one_page.tpl';
        }

        return 'mo_ingenico__flow_payment_one_page.tpl';
    }

    protected function mo_ingenico__useSelectedAlias($brand)
    {
        $brand = str_replace('alias_', '', $brand);
        if (!$alias = $this->getUser()->mo_ingenico__getCardByAlias($brand)) {
            oxRegistry::get('oxUtilsView')->addErrorToDisplay(oxNew('oxlang')->translateString('No valid alias'));
            return 'payment';
        }
        oxRegistry::getSession()->setVariable('mo_ingenico__order_alias', $brand);
        return 'order';
    }


}

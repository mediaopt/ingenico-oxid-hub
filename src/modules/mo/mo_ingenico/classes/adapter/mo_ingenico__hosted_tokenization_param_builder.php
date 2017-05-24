<?php


class mo_ingenico__hosted_tokenization_param_builder extends mo_ingenico__request_param_builder
{
    
    protected $oxidSessionParamsForRemoteCalls;

    public function build($paymentId)
    {
        $gatewayParams = array();

        //build for each paymentoption
        $paymentOptions = $this->getSdkMain()->getService('IngenicoPayments')->getIngenicoPaymentByShopPaymentId($paymentId);
        $brands = is_array($paymentOptions['brand'])?$paymentOptions['brand']:array($paymentOptions['brand']);
        foreach ($brands as $brand) {
            if (!oxNew('mo_ingenico__helper')->mo_ingenico__isBrandActive($paymentId, $brand)) {
                continue;
            }
            $params = array();
            $shopurl = $this->getOxConfig()->getSslShopUrl();

            $params['PARAMETERS.ACCEPTURL'] = $this->checkUrlLength($shopurl .    'modules/mo/mo_ingenico/scripts/mo_ingenico__redirect_iframe_parent.php?cl=payment&fnc=validatePayment', 200);
            $params['PARAMETERS.EXCEPTIONURL'] = $this->checkUrlLength($shopurl . 'modules/mo/mo_ingenico/scripts/mo_ingenico__redirect_iframe_parent.php?cl=payment&fnc=mo_ingenico__handleHostedTokenizationErrorResponse', 200);
            
            $params['CARD.PAYMENTMETHOD'] = $paymentOptions['pm'];
            if ($brand === 'MasterCard') {
                $params['CARD.BRAND'] = 'Eurocard';
            } else {
                $params['CARD.BRAND'] = $brand;
            }
            $params['ALIAS.STOREPERMANENTLY'] = 'Y';
            $params['ACCOUNT.PSPID'] = $this->getOxConfig()->getConfigParam('ingenico_sPSPID');
            $params['PARAMETERS.PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls()) . '&paymentid=' . $paymentId;

            $params = $this->handleUtf8Options($params);
            $params['SHASIGNATURE.SHASIGN'] = $this->getShaSignForParams($params);
            $gatewayParams[] = $params;
            
        }
        
        $this->getLogger()->info('HostedTokenization params built', $gatewayParams);
        
        $model = new \Mediaopt\Ingenico\Sdk\Model\RequestParameters();
        $model->setParams($gatewayParams);
        
        return $model;
    }
    
}

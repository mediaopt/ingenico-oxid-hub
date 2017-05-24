<?php


class mo_ogone__hosted_tokenization_param_builder extends mo_ogone__request_param_builder
{
    
    protected $oxidSessionParamsForRemoteCalls;

    public function build($paymentId)
    {
        $gatewayParams = array();

        //build for each paymentoption
        $paymentOptions = $this->getSdkMain()->getService('OgonePayments')->getOgonePaymentByShopPaymentId($paymentId);
        $brands = is_array($paymentOptions['brand'])?$paymentOptions['brand']:array($paymentOptions['brand']);
        foreach ($brands as $brand) {
            if (!oxNew('mo_ogone__helper')->mo_ogone__isBrandActive($paymentId, $brand)) {
                continue;
            }
            $params = array();
            $shopurl = $this->getOxConfig()->getSslShopUrl();

            $params['PARAMETERS.ACCEPTURL'] = $this->checkUrlLength($shopurl .    'modules/mo/mo_ogone/scripts/mo_ogone__redirect_iframe_parent.php?cl=payment&fnc=validatePayment', 200);
            $params['PARAMETERS.EXCEPTIONURL'] = $this->checkUrlLength($shopurl . 'modules/mo/mo_ogone/scripts/mo_ogone__redirect_iframe_parent.php?cl=payment&fnc=mo_ogone__handleHostedTokenizationErrorResponse', 200);
            
            $params['CARD.PAYMENTMETHOD'] = $paymentOptions['pm'];
            if ($brand === 'MasterCard') {
                $params['CARD.BRAND'] = 'Eurocard';
            } else {
                $params['CARD.BRAND'] = $brand;
            }
            $params['ALIAS.STOREPERMANENTLY'] = 'Y';
            $params['ACCOUNT.PSPID'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');
            $params['PARAMETERS.PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls()) . '&paymentid=' . $paymentId;

            $params = $this->handleUtf8Options($params);
            $params['SHASIGNATURE.SHASIGN'] = $this->getShaSignForParams($params);
            $gatewayParams[] = $params;
            
        }
        
        $this->getLogger()->info('HostedTokenization params built', $gatewayParams);
        
        $model = new \Mediaopt\Ogone\Sdk\Model\RequestParameters();
        $model->setParams($gatewayParams);
        
        return $model;
    }
    
}

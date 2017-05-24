<?php

class mo_ingenico__alias_param_builder extends mo_ingenico__request_param_builder
{
    
    protected $oxidSessionParamsForRemoteCalls;

    public function build($paymentId)
    {
        $gatewayParams = array();
        $oxViewConf = $this->getOxConfig()->getActiveView()->getViewConfig();

        //build for each paymentoption
        $paymentOptions = $this->getSdkMain()->getService('IngenicoPayments')->getIngenicoPaymentByShopPaymentId($paymentId);
        $brands = is_array($paymentOptions['brand'])?$paymentOptions['brand']:array($paymentOptions['brand']);
        foreach ($brands as $brand) {
            if (!oxNew('mo_ingenico__helper')->mo_ingenico__isBrandActive($paymentId, $brand)) {
                continue;
            }
            $params = array();
            $sslSelfLink = str_replace('&amp;', '&', $oxViewConf->getSslSelfLink());

            $params['ACCEPTURL'] = $this->checkUrlLength($sslSelfLink . '&cl=payment&fnc=validatePayment', 200);
            $params['EXCEPTIONURL'] = $this->checkUrlLength($sslSelfLink . '&cl=payment&fnc=validatePayment', 200);
            $params['BRAND'] = $brand;
            $params['PSPID'] = $this->getOxConfig()->getConfigParam('ingenico_sPSPID');
            $params['PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls()) . '&paymentid=' . $paymentId;
            $params['ALIASPERSISTEDAFTERUSE'] = 'Y';

            $params = $this->handleUtf8Options($params);
            $params['SHASIGN'] = $this->getShaSignForParams($params);

            $gatewayParams[] = $params;
        }
        
        $this->getLogger()->info('Alias params built', $gatewayParams);
        
        /* @var $model Mediaopt\Ingenico\Sdk\Model\RequestParameters */
        $model = new \Mediaopt\Ingenico\Sdk\Model\RequestParameters();
        $model->setParams($gatewayParams);
        
        return $model;
    }
    
}

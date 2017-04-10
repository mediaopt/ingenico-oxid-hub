<?php

class mo_ogone__alias_param_builder extends mo_ogone__request_param_builder
{
    
    protected $oxidSessionParamsForRemoteCalls;

    public function build($paymentId)
    {
        $gatewayParams = array();
        $oxViewConf = $this->getOxConfig()->getActiveView()->getViewConfig();

        //build for each paymentoption
        $paymentOptions = $this->getSdkMain()->getService('OgonePayments')->getOgonePaymentByShopPaymentId($paymentId);
        $brands = is_array($paymentOptions['brand'])?$paymentOptions['brand']:array($paymentOptions['brand']);
        foreach ($brands as $brand) {
            if (!oxNew('mo_ogone__helper')->mo_ogone__isBrandActive($paymentId, $brand)) {
                continue;
            }
            $params = array();
            $sslSelfLink = str_replace('&amp;', '&', $oxViewConf->getSslSelfLink());

            $params['ACCEPTURL'] = $this->checkUrlLength($sslSelfLink . '&cl=payment&fnc=validatePayment', 200);
            $params['EXCEPTIONURL'] = $this->checkUrlLength($sslSelfLink . '&cl=payment&fnc=validatePayment', 200);
            $params['BRAND'] = $brand;
            $params['PSPID'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');
            $params['PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls()) . '&paymentid=' . $paymentId;
            $params['ALIASPERSISTEDAFTERUSE'] = 'Y';

            $params = $this->handleUtf8Options($params);
            $params['SHASIGN'] = $this->getShaSignForParams($params);

            $gatewayParams[] = $params;
        }
        
        $this->getLogger()->logExecution($gatewayParams);
        
        /* @var $model Mediaopt\Ogone\Sdk\Model\RequestParameters */
        $model = new \Mediaopt\Ogone\Sdk\Model\RequestParameters();
        $model->setParams($gatewayParams);
        
        return $model;
    }
    
}

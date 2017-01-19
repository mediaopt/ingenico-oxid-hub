<?php

class mo_ogone__alias_param_builder extends mo_ogone__request_param_builder
{
    
    protected $oxidSessionParamsForRemoteCalls = null;

    public function build($paymentId)
    {
        $gatewayParams = array();
        $oxViewConf = $this->getOxConfig()->getActiveView()->getViewConfig();

        //build for each paymentoption
        $paymentOptions = $this->getSdkMain()->getService('OgonePayments')->getOgonePaymentByShopPaymentId($paymentId);
        foreach ($paymentOptions as $option) {
            if (!$option['active']) {
                continue;
            }
            $params = array();
            $sslSelfLink = str_replace('&amp;', '&', $oxViewConf->getSslSelfLink());

            $params['ACCEPTURL'] = $this->checkUrlLength($sslSelfLink . '&cl=payment&fnc=validatePayment', 200);
            $params['EXCEPTIONURL'] = $this->checkUrlLength($sslSelfLink . '&cl=payment&fnc=validatePayment', 200);
            $params['BRAND'] = $option['brand'];
            $params['PSPID'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');
            $params['PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls()) . '&paymentid=' . $paymentId;

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

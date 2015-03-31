<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

use Mediaopt\Ogone\Sdk\Main;

/**
 * $Id: RequestParamBuilder.php 55 2015-02-24 10:47:24Z mbe $ 
 */
class AliasParamBuilder extends RequestParamBuilder
{
    
    protected $oxidSessionParamsForRemoteCalls = null;

    public function build($paymentId)
    {
        $gatewayParams = array();
        $oxViewConf = $this->getOxConfig()->getActiveView()->getViewConfig();

        //build for each paymentoption
        $paymentOptions = Main::getInstance()->getService('OgonePayments')->getOgonePaymentByShopPaymentId($paymentId);
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
        
        $this->getAdapterMain()->getLogger()->logExecution($gatewayParams);
        
        /* @var $model Mediaopt\Ogone\Sdk\Model\RequestParameters */
        $model = $this->getSdkMain()->getModel("RequestParameters");
        $model->setParams($gatewayParams);
        
        return $model;
    }
    
}

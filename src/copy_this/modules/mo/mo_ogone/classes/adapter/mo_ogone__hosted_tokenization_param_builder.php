<?php

use Mediaopt\Ogone\Sdk\Main;

/**
 * $Id: RequestParamBuilder.php 55 2015-02-24 10:47:24Z mbe $ 
 */
class mo_ogone__hosted_tokenization_param_builder extends mo_ogone__request_param_builder
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
            $shopurl = $this->getOxConfig()->getSslShopUrl();

            $params['PARAMETERS.ACCEPTURL'] = $this->checkUrlLength($shopurl .    'modules/mo/mo_ogone/scripts/mo_ogone__redirect_iframe_parent.php?cl=payment&fnc=validatePayment', 200);
            $params['PARAMETERS.EXCEPTIONURL'] = $this->checkUrlLength($shopurl . 'modules/mo/mo_ogone/scripts/mo_ogone__redirect_iframe_parent.php?cl=payment&fnc=mo_ogone__handleHostedTokenizationErrorResponse', 200);
            
            $params['CARD.PAYMENTMETHOD'] = $option['pm'];
            if ($option['brand'] === 'MasterCard') {
                $params['CARD.BRAND'] = 'Eurocard';
            } else {
                $params['CARD.BRAND'] = $option['brand'];
            }
            $params['ACCOUNT.PSPID'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');
            $params['PARAMETERS.PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls()) . '&paymentid=' . $paymentId;

            $params = $this->handleUtf8Options($params);
            $params['SHASIGNATURE.SHASIGN'] = $this->getShaSignForParams($params);
            $gatewayParams[] = $params;
            
        }
        
        $this->getLogger()->logExecution($gatewayParams);
        
        $model = new \Mediaopt\Ogone\Sdk\Model\RequestParameters();
        $model->setParams($gatewayParams);
        
        return $model;
    }
    
}

<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

/**
 * $Id: RequestParamBuilder.php 55 2015-02-24 10:47:24Z mbe $ 
 */
class OrderDirectParamBuilder extends RequestParamBuilder
{

    protected $oxidSessionParamsForRemoteCalls = null;
    protected $billpay_itemNumber = 0;
    protected $paypal_itemNumber = 0;

    public function build()
    {
        $params = $this->prepareOrderParams();

        // OrderDirect params
        $params['userid'] = $this->getOxConfig()->getConfigParam('mo_ogone__api_userid');
        $params['pswd'] = $this->getApiUserPass();
        $sAliasUsage = '&nbsp;';
        $params['alias'] = $this->getOxSession()->getVariable('mo_ogone__order_alias');
        $params['aliasusage'] = $sAliasUsage;
        $params['rtimeout'] = \mo_ogone__main::getInstance()->getOgoneConfig()->rtimeout;

        //3D Secure
        $params['flag3d'] = 'Y';
        $params['http_accept'] = $_SERVER['HTTP_ACCEPT'];
        $params['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $params['win3ds'] = 'MAINW';

        // redirect urls
        $params['accepturl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneRedirect', 200);
        $params['declineurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneRedirect', 200);
        $params['exceptionurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneExceptionFeedbackFrom3dSecureRequest', 200);
        $params['homeurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        $params['complus'] = '';

        $params = $this->handleUtf8Options($params);
        $params['shasign'] = $this->getShaSignForParams($params);
        // mbe @TODO logExecution übernehmen
        //$this->getLogger()->logExecution($params);

        /* @var $model RequestParameters */
        $model = $this->getSdkMain()->getModel("RequestParameters");
        $model->setParams($params);
        $string = "";
        foreach ($params as $key => $val)
            $string = $string . $key . " => " . $val . "\n";
        $this->getAdapterMain()->getLogger()->info("OrderdirectParams: " . $string);
        return $model;
    }

    protected function getApiUserPass()
    {
        $pass = $this->getOxConfig()->getConfigParam('mo_ogone__api_userpass');
        return $pass;
    }

}

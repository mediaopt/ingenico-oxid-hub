<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

/**
 * $Id: RequestParamBuilder.php 55 2015-02-24 10:47:24Z mbe $ 
 */
class OrderDirectParamBuilder extends RequestParamBuilder
{

    // mbe: @TODO direkten Zugriff auf oxDB entfernen
    
    protected $oxidSessionParamsForRemoteCalls = null;
    protected $billpay_itemNumber = 0;
    protected $paypal_itemNumber = 0;

    public function build($order)
    {
        $user = $this->getOxUser();
        $params = array();
        $params['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        $params['PSPID'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');
        $params['USERID'] = $this->getOxConfig()->getConfigParam('mo_ogone__api_userid');
        $params['PSWD'] = $this->getApiUserPass();
        $params['ORDERID'] = $this->createTransID();
        $params['AMOUNT'] = $this->getFormatedOrderAmount();
        $params['CURRENCY'] = \mo_ogone__main::getInstance()->getOgoneConfig()->getOgoneCurrencyCode($this->getOxConfig()->getActShopCurrencyObject()->name);
        $params['CN'] = substr($user->oxuser__oxfname->value . ' ' . $user->oxuser__oxlname->value, 0, 35);
        $params['EMAIL'] = substr($user->oxuser__oxusername->value, 0, 50);
        $params['OWNERZIP'] = substr($user->oxuser__oxzip->value, 0, 10);
        $params['OWNERADDRESS'] = substr($user->oxuser__oxstreet->value . " " . $user->oxuser__oxstreetnr->value, 0, 35);
        $params['OWNERCTY'] = \oxDb::getDB()->getone("select oxisoalpha2 from oxcountry where oxid = '" . $user->oxuser__oxcountryid->value . "'");
        $params['OWNERTOWN'] = substr($user->oxuser__oxcity->value, 0, 25);
        $params['OPERATION'] = $this->getCreditCardOperation();
        $params['RTIMEOUT'] = \mo_ogone__main::getInstance()->getOgoneConfig()->rtimeout;

        $params['ORIG'] = \mo_ogone__main::getInstance()->getOgoneConfig()->applicationId;

        $sAliasUsage = '&nbsp;';
        $params['ALIAS'] = $this->getOxSession()->getVariable('mo_ogone__order_alias');
        $params['ALIASUSAGE'] = $sAliasUsage;



        //3D Secure
        $params['FLAG3D'] = 'Y';
        $params['HTTP_ACCEPT'] = $_SERVER['HTTP_ACCEPT'];
        $params['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        $params['WIN3DS'] = 'MAINW';
        $params['ACCEPTURL'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneRedirect', 200);
        $params['DECLINEURL'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneRedirect', 200);
        $params['EXCEPTIONURL'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneExceptionFeedbackFrom3dSecureRequest', 200);
        $params['HOMEURL'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        $params['PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls());
        $params['COMPLUS'] = '';
        $params['LANGUAGE'] = $this->getLanguageCountryCode();

        $params = $this->handleUtf8Options($params);
        $params['SHASIGN'] = $this->getShaSignForParams($params);
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

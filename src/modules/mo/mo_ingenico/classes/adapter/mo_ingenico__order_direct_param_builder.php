<?php

use Mediaopt\Ingenico\Sdk\Model\RequestParameters;

class mo_ingenico__order_direct_param_builder extends mo_ingenico__request_param_builder
{

    /**
     * generate asset url
     */
    public function getUrl()
    {
        $part1 = 'test';
        if ($this->getOxConfig()->getShopConfVar('mo_ingenico__isLiveMode')) {
            $part1 = 'prod';
        }

        $part2 = '';
        if ($this->getOxConfig()->getShopConfVar('mo_ingenico__use_utf8')) {
            $part2 = '_utf8';
        }

        return 'https://secure.ogone.com/ncol/' . $part1 . '/orderdirect' . $part2 . '.asp';
    }

    public function build()
    {
        $params = $this->prepareOrderParams();

        // OrderDirect params
        $params['userid'] = $this->getOxConfig()->getConfigParam('mo_ingenico__api_userid');
        $params['pswd'] = $this->getApiUserPass();
        $sAliasUsage = '&nbsp;';
        $params['alias'] = $this->getOxSession()->getVariable('mo_ingenico__order_alias');
        $params['aliasusage'] = $sAliasUsage;
        if ($this->getOxConfig()->getConfigParam('mo_ingenico__use_alias_manager')) {
            $params['eci'] = '9';
        }
        $params['rtimeout'] = $this->getOxConfig()->getConfigParam('mo_ingenico__timeout');

        //3D Secure
        $params['flag3d'] = 'Y';
        $params['http_accept'] = $_SERVER['HTTP_ACCEPT'];
        $params['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $params['win3ds'] = 'MAINW';
        $browserInfo = oxRegistry::get('mo_ingenico__browserinfo');
        $params['browserAcceptHeader'] = $browserInfo->getBrowserAcceptHeader();
        $params['browserColorDepth'] = $browserInfo->getBrowserColorDepth();
        $params['browserJavaEnabled'] = $browserInfo->getBrowserJavaEnabled();
        $params['browserLanguage'] = $browserInfo->getBrowserLanguage();
        $params['browserScreenHeight'] = $browserInfo->getBrowserScreenHeight();
        $params['browserScreenWidth'] = $browserInfo->getBrowserScreenWidth();
        $params['browserTimeZone'] = $browserInfo->getBrowserTimeZone();
        $params['browserUserAgent'] = $browserInfo->getBrowserUserAgent();

        // redirect urls
        $params['accepturl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
            '&fnc=mo_ingenico__fncHandleIngenicoRedirect', 200);
        $params['declineurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
            '&fnc=mo_ingenico__fncHandleIngenicoRedirect', 200);
        $params['exceptionurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
            '&fnc=mo_ingenico__fncHandleIngenicoRedirect', 200);
        $params['homeurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        $params['complus'] = '';

        $params = $this->handleUtf8Options($params);
        $params['shasign'] = $this->getShaSignForParams($params);

        /* @var $model RequestParameters */
        $model = new RequestParameters();
        $model->setParams($params);
        $this->getLogger()->info('OrderDirect params built', $params);
        return $model;
    }

    protected function getApiUserPass()
    {
        return $this->getOxConfig()->getConfigParam('mo_ingenico__api_userpass');
    }

}

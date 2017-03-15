<?php

use Mediaopt\Ogone\Sdk\Model\RequestParameters;

class mo_ogone__order_direct_param_builder extends mo_ogone__request_param_builder
{

    /**
     * generate asset url
     */
    public function getUrl()
    {
        $part1 = 'test';
        if ($this->getOxConfig()->getShopConfVar('mo_ogone__isLiveMode')) {
            $part1 = 'prod';
        }

        $part2 = '';
        if ($this->getOxConfig()->getShopConfVar('mo_ogone__use_utf8')) {
            $part2 = '_utf8';
        }

        return 'https://secure.ogone.com/ncol/' . $part1 . '/orderdirect' . $part2 . '.asp';
    }

    public function build()
    {
        $params = $this->prepareOrderParams();

        // OrderDirect params
        $params['userid'] = $this->getOxConfig()->getConfigParam('mo_ogone__api_userid');
        $params['pswd'] = $this->getApiUserPass();
        $sAliasUsage = '&nbsp;';
        $params['alias'] = $this->getOxSession()->getVariable('mo_ogone__order_alias');
        $params['aliasusage'] = $sAliasUsage;
        $params['rtimeout'] = $this->getOxConfig()->getConfigParam('mo_ogone__timeout');

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
            '&fnc=mo_ogone__fncHandleOgoneRedirect', 200);
        $params['homeurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        $params['complus'] = '';

        $params = $this->handleUtf8Options($params);
        $params['shasign'] = $this->getShaSignForParams($params);

        /* @var $model RequestParameters */
        $model = new RequestParameters();
        $model->setParams($params);
        $string = "";
        foreach ($params as $key => $val) {
            $string = $string . $key . ' => ' . $val . "\n";
        }
        $this->getLogger()->info('OrderdirectParams: ' . $string);
        return $model;
    }

    protected function getApiUserPass()
    {
        return $this->getOxConfig()->getConfigParam('mo_ogone__api_userpass');
    }

}

<?php

use Mediaopt\Ogone\Sdk\Main;

class mo_ogone__request_param_builder extends mo_ogone__abstract_factory
{

    // mbe: @TODO direkten Zugriff auf oxDB entfernen

    protected $oxidSessionParamsForRemoteCalls = null;

    protected function getShaSignForParams($params)
    {
        $signatureBuilder = $this->getSdkMain()->getService("SignatureBuilder");
        return $signatureBuilder->hash(
                        $signatureBuilder->build(
                                $params, $this->getOxConfig()->getConfigParam('ogone_sSecureKeyIn')));
    }

    protected function getOxidSessionParamsForRemoteCalls()
    {
        if ($this->oxidSessionParamsForRemoteCalls === null) {
            $this->oxidSessionParamsForRemoteCalls = array();
            $this->oxidSessionParamsForRemoteCalls['ord_agb'] = true;
            $this->oxidSessionParamsForRemoteCalls['sess_challenge'] = $this->getOxSession()->getVariable('sess_challenge');
            $this->oxidSessionParamsForRemoteCalls[$this->getOxSession()->getName()] = $this->getOxSession()->getId();
            $this->oxidSessionParamsForRemoteCalls['stoken'] = $this->getOxSession()->getSessionChallengeToken();
            $this->oxidSessionParamsForRemoteCalls['rtoken'] = $this->getOxSession()->getRemoteAccessToken(true);
            $this->oxidSessionParamsForRemoteCalls['shopid'] = $this->getOxConfig()->getShopId();
            $this->oxidSessionParamsForRemoteCalls['sDeliveryAddressMD5'] = $this->getOxSession()->getUser()->getEncodedDeliveryAddress();
            if($oDelAddress = $this->getDelAddressInfo()){
                $this->oxidSessionParamsForRemoteCalls['sDeliveryAddressMD5'] .= $oDelAddress->getEncodedDeliveryAddress();
            }
        }
        return $this->oxidSessionParamsForRemoteCalls;
    }

    /**
     * Loads and returns delivery address object or null
     * if deladrid is not configured, or object was not loaded
     *
     * @return  object
     */
    protected function getDelAddressInfo()
    {
        $oDelAdress = null;
        if (! ($soxAddressId = $this->getOxConfig()->getRequestParameter( 'deladrid' ) ) ) {
            $soxAddressId = $this->getOxSession()->getVariable( 'deladrid' );
        }
        if ( $soxAddressId ) {
            $oDelAdress = oxNew( 'oxaddress' );
            $oDelAdress->load( $soxAddressId );
        }
        return $oDelAdress;
    }

    /**
     * handle utf8 options
     * 
     * @param array $params
     * @return array 
     */
    protected function handleUtf8Options($params)
    {
        if ($this->getOxConfig()->isUtf() && !$this->getOxConfig()->getShopConfVar('mo_ogone__use_utf8')) {
            foreach ($params as $name => $value) {
                $params[$name] = utf8_decode($params[$name]);
            }
        }
        return $params;
    }

    protected function getLanguageCountryCode()
    {
        return \mo_ogone__main::getInstance()->getOgoneConfig()->getLanguageCountryCodeForOxidLanguageCode($this->getOxLang()->getLanguageAbbr());
    }

    protected function getCreditCardOperation()
    {
        return $this->getOxConfig()->getShopConfVar('mo_ogone__capture_creditcard') ? 'RES' : 'SAL';
    }

    protected function checkUrlLength($url, $maxLength)
    {
        if (strlen($url) > $maxLength) {
            $this->getLogger()->error("Url is longer than $maxLength: $url");
        }
        return $url;
    }

    /**
     * Creates a unique transaction ID for the Ogone call
     * @return string
     */
    protected function createTransID()
    {
        return uniqid("mo_ogone_", true);
    }

    protected function getFormatedOrderAmount()
    {
        $dAmount = $this->getOxSession()->getBasket()->getPrice()->getBruttoPrice();
        $dAmount = number_format($dAmount, 2, '.', '');
        $dAmount = $dAmount * 100;
        $dAmount = round($dAmount, 0);
        $dAmount = substr($dAmount, 0, 15);
        return $dAmount;
    }

    protected function getBillProperty($param)
    {
        if ($param === "email") {
            $field = "oxuser__oxusername";
        } else {
            $field = "oxuser__ox" . $param;
        }
        return $this->getOxUser()->$field->value;
    }

    protected function getDelProperty($param)
    {
        $user = $this->getOxUser();
        if ($user->getSelectedAddressId()) {
            $field = "oxaddress__ox" . $param;
            $deliveryAddress = $user->getSelectedAddress();
            return $deliveryAddress->$field->value;
        }
        return null;
    }

    protected function prepareOrderParams()
    {
        $params = array(
            'remote_addr' => $_SERVER['REMOTE_ADDR'],
            'pspid' => substr($this->getOxConfig()->getConfigParam('ogone_sPSPID'), 0, 30),
            'orderid' => $this->createTransID(),
            'amount' => $this->getFormatedOrderAmount(),
            'currency' => mo_ogone__main::getInstance()->getOgoneConfig()->getOgoneCurrencyCode($this->getOxConfig()->getActShopCurrencyObject()->name),
            'operation' => $this->getCreditCardOperation(),
            'language' => $this->getLanguageCountryCode(),
            'cn' => substr($this->getBillProperty("fname") . ' ' . $this->getBillProperty("lname"), 0, 35),
            'email' => substr($this->getBillProperty("email"), 0, 50),
            'ownerzip' => substr($this->getBillProperty("zip"), 0, 10),
            'owneraddress' => substr($this->getBillProperty("street") . " " . $this->getBillProperty("streetnr"), 0, 35),
            'ownercty' => oxDb::getDB()->getone("select oxisoalpha2 from oxcountry where oxid = '" . $this->getBillProperty("countryid") . "'"),
            'ownertown' => substr($this->getBillProperty("city"), 0, 25),
            'orig' => mo_ogone__main::getInstance()->getOgoneConfig()->applicationId,
            'paramplus' => substr(http_build_query($this->getOxidSessionParamsForRemoteCalls()), 0, 1000)
        );
        return $params;
    }

}

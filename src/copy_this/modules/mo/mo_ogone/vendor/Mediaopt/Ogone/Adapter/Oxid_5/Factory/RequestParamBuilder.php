<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

/**
 * $Id: RequestParamBuilder.php 55 2015-02-24 10:47:24Z mbe $ 
 */
class RequestParamBuilder extends AbstractFactory
{

    // mbe: @TODO direkten Zugriff auf oxDB entfernen
    
    protected $oxidSessionParamsForRemoteCalls = null;

    protected function getShaSignForParams($params)
    {
        $signatureBuilder = Main::getInstance()->getService("SignatureBuilder");
        return $signatureBuilder->hash(
                        $signatureBuilder->build(
                                $params, $this->getOxConfig()->getConfigParam('ogone_sSecureKeyIn')));
    }

    protected function getOxidSessionParamsForRemoteCalls()
    {
        if ($this->oxidSessionParamsForRemoteCalls === null) {
            $this->oxidSessionParamsForRemoteCalls = array();
            $this->oxidSessionParamsForRemoteCalls['sess_challenge'] = $this->getOxSession()->getVariable('sess_challenge');
            $this->oxidSessionParamsForRemoteCalls[$this->getOxSession()->getName()] = $this->getOxSession()->getId();
            $this->oxidSessionParamsForRemoteCalls['stoken'] = $this->getOxSession()->getSessionChallengeToken();
            $this->oxidSessionParamsForRemoteCalls['rtoken'] = $this->getOxSession()->getRemoteAccessToken(true);
            $this->oxidSessionParamsForRemoteCalls['sDeliveryAddressMD5'] = $this->getOxSession()->getUser()->getEncodedDeliveryAddress();
        }
        return $this->oxidSessionParamsForRemoteCalls;
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
    protected function createTransID(){
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

    /**
   * fetch billing & delivery address data
   * 
   * @return array
   */
  protected function getUserData()
  {
    $userData = array();
    $oxUser = $this->oxConfig->getUser();

    if (!$oxUser)
    {
      return $userData;
    }

    //billingaddress
    $userData['customer_id']          = $oxUser->oxuser__oxcustnr->value;
    $userData['customer_company']     = $oxUser->oxuser__oxcompany->value;
    $userData['customer_gender']      = $oxUser->oxuser__oxsal->value == 'MR' ? 'm' : 'f';
    $userData['customer_firstname']   = $oxUser->oxuser__oxfname->value;
    $userData['customer_lastname']    = $oxUser->oxuser__oxlname->value;
    $userData['customer_street']      = $oxUser->oxuser__oxstreet->value;
    $userData['customer_houseNumber'] = $oxUser->oxuser__oxstreetnr->value;
    $userData['customer_postcode']    = $oxUser->oxuser__oxzip->value;
    $userData['customer_city']        = $oxUser->oxuser__oxcity->value;
    $userData['customer_country']     = $this->getCountryCode($oxUser->oxuser__oxcountryid->value);
    $userData['customer_dateOfBirth'] = $this->getParamDateOfBirth();
    $userData['customer_email']       = $oxUser->oxuser__oxusername->value;
    $userData['customer_phone']       = $oxUser->oxuser__oxfon->value;


    if ($oxUser->getSelectedAddressId())
    {
      $deliveryAddress                         = $oxUser->getSelectedAddress();
      $userData['deliveryAddress_company']     = $deliveryAddress->oxaddress__oxcompany->value;
      $userData['deliveryAddress_gender']      = $deliveryAddress->oxaddress__oxsal->value == 'MR' ? 'm' : 'f';
      $userData['deliveryAddress_firstname']   = $deliveryAddress->oxaddress__oxfname->value;
      $userData['deliveryAddress_lastname']    = $deliveryAddress->oxaddress__oxlname->value;
      $userData['deliveryAddress_street']      = $deliveryAddress->oxaddress__oxstreet->value;
      $userData['deliveryAddress_houseNumber'] = $deliveryAddress->oxaddress__oxstreetnr->value;
      $userData['deliveryAddress_postcode']    = $deliveryAddress->oxaddress__oxzip->value;
      $userData['deliveryAddress_city']        = $deliveryAddress->oxaddress__oxcity->value;
      $userData['deliveryAddress_country']     = $this->getCountryCode($deliveryAddress->oxaddress__oxcountryid->value);
    }

    return $userData;
  }
    
}

<?php

/**
 * $Id: mo_ogone__config.php 6 2012-12-12 10:16:57Z martin $ 
 */
class mo_ogone__config
{

  public function __construct($configFile, $logger)
  {
    $this->logger = $logger;

    include $configFile;

    $this->logger->setConfig($this);

    $this->init();
  }

  /**
   * build data structure (can be re-triggered from outside)
   */
  public function init()
  {
    $this->ogonePaymentsByOxidPaymentId =
            $this->createOgonePaymentsByOxidPaymentId($this->paymentMethods);
  }

  public function getPaymentMethodProperty($oxidPaymentId, $property)
  {
    if (!isset($this->ogonePaymentsByOxidPaymentId[$oxidPaymentId]))
    {
      throw new Exception("No Payment with oxidPaymentId: $oxidPaymentId found!");
    }
    if (!isset($this->ogonePaymentsByOxidPaymentId[$oxidPaymentId][0][$property]))
    {
      throw new Exception("Property $property not found!");
    }
    if ($property === 'paymenttype' && !oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_hidden_auth')) {
        return MO_OGONE__PAYMENTTYPE_REDIRECT;
    }
    return $this->ogonePaymentsByOxidPaymentId[$oxidPaymentId][0][$property];
  }

  public function getOxidPaymentIds()
  {
    return array_keys($this->ogonePaymentsByOxidPaymentId);
  }

  protected function createOgonePaymentsByOxidPaymentId($paymentMethods)
  {
    $result = array();
    foreach ($paymentMethods as $paymentMethod)
    {
      $paymentMethod['active']                      = $this->isPaymentOptionActive($paymentMethod);
      $result[$paymentMethod['oxid_payment_id']] [] = $paymentMethod;
    }
    return $result;
  }

  protected function isPaymentOptionActive($paymentMethod)
  {
    $options = $this->getPaymentOptionsByOxidPaymentId($paymentMethod['oxid_payment_id']);
    return in_array($paymentMethod['brand'], $options);
  }

  protected function getPaymentOptionsByOxidPaymentId($oxidPaymentId)
  {
    $options = oxRegistry::getConfig()->getConfigParam('mo_ogone__paymentOptions');
    return isset($options[$oxidPaymentId]) ? $options[$oxidPaymentId] : array();
  }

  public function getPaymenttypeByOxidPaymentId($oxidPaymentId)
  {
    return isset($this->ogonePaymentsByOxidPaymentId[$oxidPaymentId]['paymenttype']) ? $this->ogonePaymentsByOxidPaymentId[$oxidPaymentId]['paymenttype'] : null;
  }

  public function getOgonePayments()
  {
    return $this->ogonePaymentsByOxidPaymentId;
  }

  public function getOgonePaymentByOxidPaymentId($paymentId)
  {
    return isset($this->ogonePaymentsByOxidPaymentId[$paymentId]) ? $this->ogonePaymentsByOxidPaymentId[$paymentId] : array();
  }

  public function getLanguageCountryCodeForOxidLanguageCode($oxidLanguageCode)
  {
    if (!isset($this->oxidLangCodeToOgoneLanguageCountryCode[$oxidLanguageCode]))
    {
      $this->logger->error(
              "Not supported language code: '$oxidLanguageCode', supported codes are: " .
              implode(', ', array_keys($this->oxidLangCodeToOgoneLanguageCountryCode)));
    }
    return $this->oxidLangCodeToOgoneLanguageCountryCode[$oxidLanguageCode];
  }

  public function getOgoneCurrencyCode($oxidCurrencyCode)
  {
    // just check if the code is supported
    if (!in_array($oxidCurrencyCode, $this->supportedCurrencies))
    {
      $this->logger->error(
              "Not supported currency code: '$oxidCurrencyCode', supported codes are: " .
              implode(', ', $this->supportedCurrencies) . ', please ask Ogone for support.');
    }
    return $oxidCurrencyCode;
  }

}


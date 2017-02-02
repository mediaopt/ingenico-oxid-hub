<?php

/**
 * $Id: mo_ogone__config.php 6 2012-12-12 10:16:57Z martin $ 
 */
class mo_ogone__config
{
  public function __construct($configFile, $logger)
  {
    $this->logger = $logger;

    //@todo refactor
    include $configFile;

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


<?php

/**
 * $Id: mo_ogone__authenticator.php 53 2014-11-28 13:54:26Z martin $ 
 */

/**
 * Authenticates so called SHAOut-Requests
 *  SHAOut => Response from Ogone
 *  SHAIn  => Requests from Oxid to Ogone
 */
class mo_ogone__authenticator
{

  public function __construct($requestParams, $signatureBuilder, $oxConfig, $logger)
  {
    $this->requestParams = $requestParams;
    $this->signatureBuilder = $signatureBuilder;
    $this->oxConfig = $oxConfig;
    $this->logger = $logger;
  }

  // TODO: HK: Check signature with umlauts and different encodings
  public function authenticateRequest()
  {
    if (!$this->oxConfig->getRequestParameter('SHASIGN'))
    {
      $this->logger->error("SHA-Sign Parameter not present - please check Ogone-Backend settings (technical-information" .
              " => transaction-feedback => I want to receive transaction feedback[...])", $_REQUEST);
      return false;
    }

    $signature =
            $this->signatureBuilder->build(
            $this->signatureBuilder->filterResponseParams($this->requestParams), $this->oxConfig->getConfigParam('ogone_sSecureKeyOut'));

    $signature = $this->signatureBuilder->hash($signature);

    if ($this->oxConfig->getRequestParameter('SHASIGN') == $signature)
    {
      return true;
    }

    $this->logger->error("SECURE KEY INCORRECT - Received from IP: " . $_SERVER["REMOTE_ADDR"], $_REQUEST);
    return false;
  }

}
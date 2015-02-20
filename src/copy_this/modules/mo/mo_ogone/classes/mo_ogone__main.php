<?php

/**
 * $Id: mo_ogone__main.php 7 2012-12-12 10:44:58Z martin $ 
 */
class mo_ogone__main
{

  static protected $instance;

  static public function getInstance()
  {
    if (!isset(self::$instance))
    {
      self::$instance = new self;
    }
    return self::$instance;
  }

  protected function __construct()
  {
    $this->logger = null;
    $this->feedbackHandler = null;
    $this->authenticator = null;
    $this->oxConfig = null;
    $this->oxSession = null;
    $this->oxUtilsObject = null;
    $this->oxLang = null;
    $this->ogoneConfig = null;
    $this->requestParamBuilder = null;
    $this->signatureBuilder = null;
  }

  public function getLogger()
  {
    if (!isset($this->logger))
    {
      $this->logger = new mo_ogone__logger;
      $this->getOgoneConfig(); // force config initialization, so logger is configured
    }
    return $this->logger;
  }

  public function getFeedbackHandler()
  {
    if (!isset($this->feedbackHandler))
    {
      $this->feedbackHandler = new mo_ogone__feedback_handler(
                      $this->getLogger(),
                      $this->getAuthenticator(),
                      $this->getOxUtilsObject()
      );
    }
    return $this->feedbackHandler;
  }

  public function getAuthenticator()
  {
    if (!isset($this->authenticator))
    {
      $this->authenticator = new mo_ogone__authenticator(
                      $_REQUEST,
                      $this->getSignatureBuilder(),
                      $this->getOxConfig(),
                      $this->getLogger()
      );
    }
    return $this->authenticator;
  }

  public function getOxConfig()
  {
    if (!isset($this->oxConfig))
    {
      $this->oxConfig = oxRegistry::getConfig();
    }
    return $this->oxConfig;
  }

  public function getOxSession()
  {
    if (!isset($this->oxSession))
    {
      $this->oxSession = oxRegistry::getSession();
    }
    return $this->oxSession;
  }

  public function getOxUtilsObject()
  {
    if (!isset($this->oxUtilsObject))
    {
      $this->oxUtilsObject = oxUtilsObject::getInstance();
    }
    return $this->oxUtilsObject;
  }

  public function getOxLang()
  {
    if (!isset($this->oxLang))
    {
      $this->oxLang = oxRegistry::getLang();
    }
    return $this->oxLang;
  }

  public function getOgoneConfig()
  {
    if (!isset($this->ogoneConfig))
    {
      $configFile = realpath(dirname(__FILE__) . '/..') . '/config.php';
      $this->ogoneConfig = new mo_ogone__config($configFile, $this->getLogger());
    }
    return $this->ogoneConfig;
  }

  public function getRequestParamBuilder()
  {
    if (!isset($this->requestParamBuilder))
    {
      $this->requestParamBuilder = new mo_ogone__request_param_builder(
                      $this->getOxSession(),
                      $this->getOxConfig(),
                      $this->getOgoneConfig(),
                      $this->getOxLang(),
                      $this->getSignatureBuilder(),
                      $this->getLogger()
      );
    }
    return $this->requestParamBuilder;
  }

  protected function getSignatureBuilder()
  {
    if (!isset($this->signatureBuilder))
    {
      $this->signatureBuilder = new mo_ogone__signature_builder(
                      $this->getOgoneConfig()->shaOutParameters,
                      $this->getOxConfig()->getConfigParam('ogone_sHashingAlgorithm')
      );
    }
    return $this->signatureBuilder;
  }

}
<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;


class OgonePayments extends AbstractService
{

    public function __construct($adapter, \Mediaopt\Ogone\Sdk\Client $client, \Mediaopt\Ogone\Sdk\Helper $helper)
    {
        parent::__construct($adapter, $client, $helper);
        $this->init();
    }
  /**
   * build data structure (can be re-triggered from outside)
   */
  public function init()
  {
    $this->ogonePaymentsByShopPaymentId =
            $this->createOgonePaymentsByShopPaymentId(\Mediaopt\Ogone\Sdk\Config::getPaymentMethods());
  }

  public function getPaymentMethodProperty($shopPaymentId, $property)
  {
    if (!isset($this->ogonePaymentsByShopPaymentId[$shopPaymentId]))
    {
      throw new Exception("No Payment with shopPaymentId: $shopPaymentId found!");
    }
    if (!isset($this->ogonePaymentsByShopPaymentId[$shopPaymentId][0][$property]))
    {
      throw new Exception("Property $property not found!");
    }
    // @TODO mbe remove oxRegistry
    if ($property === 'paymenttype' && !\oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_hidden_auth')) {
        return MO_OGONE__PAYMENTTYPE_REDIRECT;
    }
    return $this->ogonePaymentsByShopPaymentId[$shopPaymentId][0][$property];
  }

  public function getShopPaymentIds()
  {
    return array_keys($this->ogonePaymentsByShopPaymentId);
  }

  protected function createOgonePaymentsByShopPaymentId($paymentMethods)
  {
    $result = array();
    foreach ($paymentMethods as $paymentMethod)
    {
      $paymentMethod['active']                      = $this->isPaymentOptionActive($paymentMethod);
      $result[$paymentMethod['shop_payment_id']] [] = $paymentMethod;
    }
    return $result;
  }

  protected function isPaymentOptionActive($paymentMethod)
  {
    $options = $this->getPaymentOptionsByShopPaymentId($paymentMethod['shop_payment_id']);
    return in_array($paymentMethod['brand'], $options);
  }

  protected function getPaymentOptionsByShopPaymentId($shopPaymentId)
  {
    // @TODO mbe remove oxRegistry
    $options = \oxRegistry::getConfig()->getConfigParam('mo_ogone__paymentOptions');
    return isset($options[$shopPaymentId]) ? $options[$shopPaymentId] : array();
  }

  public function getPaymenttypeByShopPaymentId($shopPaymentId)
  {
    return isset($this->ogonePaymentsByShopPaymentId[$shopPaymentId]['paymenttype']) ? $this->ogonePaymentsByShopPaymentId[$shopPaymentId]['paymenttype'] : null;
  }

  public function getOgonePayments()
  {
    return $this->ogonePaymentsByShopPaymentId;
  }

  public function getOgonePaymentByShopPaymentId($paymentId)
  {
    return isset($this->ogonePaymentsByShopPaymentId[$paymentId]) ? $this->ogonePaymentsByShopPaymentId[$paymentId] : array();
  }

}

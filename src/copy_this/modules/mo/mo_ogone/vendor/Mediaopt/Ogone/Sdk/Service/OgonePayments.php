<?php

namespace Mediaopt\Ogone\Sdk\Service;

class OgonePayments extends AbstractService
{

    /**
     * @todo check if this property is better handled as static prop
     * @var array
     */
    protected $ogonePaymentsByShopPaymentId = array(
        // credit card payments (one page)
        'ogone_credit_card' => array(
        'pm'              => 'CreditCard',
        'brand'           => ['VISA', 'MasterCard', 'American Express', 'JCB', 'Diners Club', 'Dankort'],
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
        // debit card payments (redirect)
        'ogone_maestro' => array(
        'pm'              => 'CreditCard',
        'brand'           => 'Maestro',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        // PostFinance Card (redirect)
        'ogone_post_card' => array(
        'pm'              => 'PostFinance Card',
        'brand'           => 'PostFinance + card',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        // direct banking payments (redirect)
        'ogone_giropay' => array(
        'pm'              => 'giropay',
        'brand'           => 'giropay',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        'ogone_sofortueberweisung' => array(
        'pm'              => 'DirectEbanking',
        'brand'           => 'Sofort Uberweisung',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        'ogone_ideal' => array(
        'pm'              => 'iDEAL',
        'brand'           => 'iDEAL',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        'ogone_post_efinance' => array(
        'pm'              => 'PostFinance e-finance',
        'brand'           => 'PostFinance e-finance',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        'ogone_eps' => array(
        'pm'              => 'EPS',
        'brand'           => 'EPS',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        // direct debit payments (one-page, but at the moment only redirect is supported)
        'ogone_debit_de' => array(
        'pm'              => 'Direct Debits DE',
        'brand'           => 'Direct Debits DE',
        //~ 'paymenttype' => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        'ogone_debit_nl' => array(
        'pm'              => 'Direct Debits NL',
        'brand'           => 'Direct Debits NL',
        //~ 'paymenttype' => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        // e-Wallet payments (redirect)
        'ogone_paypal' => array(
        'pm'              => 'PAYPAL',
        'brand'           => 'PAYPAL',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        // Mobile payments (redirect)
        'ogone_mpass' => array(
        'pm'              => 'MPASS',
        'brand'           => 'MPASS',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        // BillPay (redirect)
        'ogone_open_invoice_de' => array(
        'pm'              => 'Open Invoice DE',
        'brand'           => 'Open Invoice DE',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        // Mister Cash (redirect)
        'ogone_mister_cash' => array(
        'pm'              => 'CreditCard',
        'brand'           => 'Bancontact/Mister Cash',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        // VVV (redirect)
        'ogone_vvv' => array(
        'pm'              => 'Intersolve',
        'brand'           => 'VVV Giftcard',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        );

  public function getPaymentMethodProperty($shopPaymentId, $property)
  {
    if (!isset($this->ogonePaymentsByShopPaymentId[$shopPaymentId]))
    {
      throw new \Exception("No Payment with shopPaymentId: $shopPaymentId found!");
    }
    if (!isset($this->ogonePaymentsByShopPaymentId[$shopPaymentId][$property]))
    {
      throw new \Exception("Property $property not found!");
    }
    return $this->ogonePaymentsByShopPaymentId[$shopPaymentId][$property];
  }

  public function getShopPaymentIds()
  {
    return array_keys($this->ogonePaymentsByShopPaymentId);
  }

  public function getOgonePaymentByShopPaymentId($paymentId)
  {
    return isset($this->ogonePaymentsByShopPaymentId[$paymentId]) ? $this->ogonePaymentsByShopPaymentId[$paymentId] : array();
  }

}

<?php

namespace Mediaopt\Ingenico\Sdk\Service;

class IngenicoPayments extends AbstractService
{

    /**
     * @todo check if this property is better handled as static prop
     * @var array
     */
    protected $ingenicoPaymentsByShopPaymentId = array(
        // credit card payments (one page)
        'ingenico_credit_card' => array(
        'pm'              => 'CreditCard',
        'brand'           => ['VISA', 'MasterCard', 'American Express', 'JCB', 'Diners Club', 'Dankort'],
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_ONE_PAGE),
        // debit card payments (redirect)
        'ingenico_maestro' => array(
        'pm'              => 'CreditCard',
        'brand'           => 'Maestro',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        // PostFinance Card (redirect)
        'ingenico_post_card' => array(
        'pm'              => 'PostFinance Card',
        'brand'           => 'PostFinance + card',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        // direct banking payments (redirect)
        'ingenico_giropay' => array(
        'pm'              => 'giropay',
        'brand'           => 'giropay',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisung' => array(
        'pm'              => 'DirectEbanking',
        'brand'           => 'Sofort Uberweisung',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisungAT' => array(
        'pm'              => 'DirectEbankingAT',
        'brand'           => 'Sofort UberweisungAT',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisungBE' => array(
        'pm'              => 'DirectEbankingBE',
        'brand'           => 'Sofort UberweisungBE',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisungCH' => array(
        'pm'              => 'DirectEbankingCH',
        'brand'           => 'Sofort UberweisungCH',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisungDE' => array(
        'pm'              => 'DirectEbankingDE',
        'brand'           => 'Sofort UberweisungDE',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisungFR' => array(
        'pm'              => 'DirectEbankingFR',
        'brand'           => 'Sofort UberweisungFR',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisungGB' => array(
        'pm'              => 'DirectEbankingGB',
        'brand'           => 'Sofort UberweisungGB',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisungIT' => array(
        'pm'              => 'DirectEbankingIT',
        'brand'           => 'Sofort UberweisungIT',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_sofortueberweisungNL' => array(
        'pm'              => 'DirectEbankingNL',
        'brand'           => 'Sofort UberweisungNL',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_ideal' => array(
        'pm'              => 'iDEAL',
        'brand'           => 'iDEAL',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_post_efinance' => array(
        'pm'              => 'PostFinance e-finance',
        'brand'           => 'PostFinance e-finance',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_eps' => array(
        'pm'              => 'EPS',
        'brand'           => 'EPS',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        // direct debit payments (one-page, but at the moment only redirect is supported)
        'ingenico_debit_de' => array(
        'pm'              => 'Direct Debits DE',
        'brand'           => 'Direct Debits DE',
        //~ 'paymenttype' => MO_INGENICO__PAYMENTTYPE_ONE_PAGE),
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        'ingenico_debit_nl' => array(
        'pm'              => 'Direct Debits NL',
        'brand'           => 'Direct Debits NL',
        //~ 'paymenttype' => MO_INGENICO__PAYMENTTYPE_ONE_PAGE),
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        // e-Wallet payments (redirect)
        'ingenico_paypal' => array(
        'pm'              => 'PAYPAL',
        'brand'           => 'PAYPAL',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        // Mobile payments (redirect)
        'ingenico_mpass' => array(
        'pm'              => 'MPASS',
        'brand'           => 'MPASS',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        // BillPay (redirect)
        'ingenico_open_invoice_de' => array(
        'pm'              => 'Open Invoice DE',
        'brand'           => 'Open Invoice DE',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        // Mister Cash (redirect)
        'ingenico_mister_cash' => array(
        'pm'              => 'CreditCard',
        'brand'           => 'Bancontact/Mister Cash',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        // VVV (redirect)
        'ingenico_vvv' => array(
        'pm'              => 'Intersolve',
        'brand'           => 'VVV Giftcard',
        'paymenttype'     => MO_INGENICO__PAYMENTTYPE_REDIRECT),
        );

  public function getPaymentMethodProperty($shopPaymentId, $property)
  {
    if (!isset($this->ingenicoPaymentsByShopPaymentId[$shopPaymentId]))
    {
      throw new \Exception("No Payment with shopPaymentId: $shopPaymentId found!");
    }
    if (!isset($this->ingenicoPaymentsByShopPaymentId[$shopPaymentId][$property]))
    {
      throw new \Exception("Property $property not found!");
    }
    return $this->ingenicoPaymentsByShopPaymentId[$shopPaymentId][$property];
  }

  public function getShopPaymentIds()
  {
    return array_keys($this->ingenicoPaymentsByShopPaymentId);
  }

  public function getIngenicoPaymentByShopPaymentId($paymentId)
  {
    return isset($this->ingenicoPaymentsByShopPaymentId[$paymentId]) ? $this->ingenicoPaymentsByShopPaymentId[$paymentId] : array();
  }

}

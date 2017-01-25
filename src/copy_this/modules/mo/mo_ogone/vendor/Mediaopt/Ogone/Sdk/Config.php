<?php

namespace Mediaopt\Ogone\Sdk;

class Config {

    // Mapping from id to payment-method(pm) and brand
    public static function getPaymentMethods() {
        return array(
            // credit card payments (one page)
            array(
                'shop_payment_id' => 'ogone_credit_card',
                'pm'              => 'CreditCard',
                'brand'           => 'VISA',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
            array(
                'shop_payment_id' => 'ogone_credit_card',
                'pm'              => 'CreditCard',
                'brand'           => 'MasterCard',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
            array(
                'shop_payment_id' => 'ogone_credit_card',
                'pm'              => 'CreditCard',
                'brand'           => 'American Express',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
            array(
                'shop_payment_id' => 'ogone_credit_card',
                'pm'              => 'CreditCard',
                'brand'           => 'JCB',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
            array(
                'shop_payment_id' => 'ogone_credit_card',
                'pm'              => 'CreditCard',
                'brand'           => 'Diners Club',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
            // debit card payments (redirect)
            array(
                'shop_payment_id' => 'ogone_maestro',
                'pm'              => 'CreditCard',
                'brand'           => 'Maestro',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            array(
                'shop_payment_id' => 'ogone_post_card',
                'pm'              => 'PostFinance Card',
                'brand'           => 'PostFinance + card',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            // direct banking payments (redirect)
            array(
                'shop_payment_id' => 'ogone_giropay',
                'pm'              => 'giropay',
                'brand'           => 'giropay',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            array(
                'shop_payment_id' => 'ogone_sofortueberweisung',
                'pm'              => 'DirectEbanking',
                'brand'           => 'Sofort Uberweisung',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            array(
                'shop_payment_id' => 'ogone_ideal',
                'pm'              => 'iDEAL',
                'brand'           => 'iDEAL',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            array(
                'shop_payment_id' => 'ogone_post_efinance',
                'pm'              => 'PostFinance e-finance',
                'brand'           => 'PostFinance e-finance',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            array(
                'shop_payment_id' => 'ogone_eps',
                'pm'              => 'EPS',
                'brand'           => 'EPS',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            // direct debit payments (one-page, but at the moment only redirect is supported)
            array(
                'shop_payment_id' => 'ogone_debit_de',
                'pm'              => 'Direct Debits DE',
                'brand'           => 'Direct Debits DE',
                //~ 'paymenttype' => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            array(
                'shop_payment_id' => 'ogone_debit_nl',
                'pm'              => 'Direct Debits NL',
                'brand'           => 'Direct Debits NL',
                //~ 'paymenttype' => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            // e-Wallet payments (redirect)
            array(
                'shop_payment_id' => 'ogone_paypal',
                'pm'              => 'PAYPAL',
                'brand'           => 'PAYPAL',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            // Mobile payments (redirect)
            array(
                'shop_payment_id' => 'ogone_mpass',
                'pm'              => 'MPASS',
                'brand'           => 'MPASS',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            // BillPay (redirect)
            array(
                'shop_payment_id' => 'ogone_open_invoice_de',
                'pm'              => 'Open Invoice DE',
                'brand'           => 'Open Invoice DE',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            // Mister Cash (redirect)
            array(
                'shop_payment_id' => 'ogone_mister_cash',
                'pm'              => 'CreditCard',
                'brand'           => 'Bancontact/Mister Cash',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            /// VVV (redirect)
            array(
                'shop_payment_id' => 'ogone_vvv',
                'pm'              => 'Intersolve',
                'brand'           => 'VVV Giftcard',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
            // Dankort (redirect)
            array(
                'shop_payment_id' => 'ogone_credit_card',
                'pm'              => 'CreditCard',
                'brand'           => 'Dankort',
                'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
        );
    }
}
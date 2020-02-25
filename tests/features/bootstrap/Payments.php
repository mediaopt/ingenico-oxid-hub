<?php

/**
 * @author  Mediaopt GmbH
 * @author  ibrahim.hilali@mediaopt.de
 */
interface Payments
{
    /**
     * @var string[]
     */
    const ACTIVATE_PAYMENTS = [
        'paypal'                            => self::PAYPAL_PAYMENT,
        'direct-pay-for-non-german-account' => self::NON_GERMAN_ACCOUNT_PAYMENT,
        'direct-pay-for-german-account'     => self::GERMAN_ACCOUNT_PAYMENT,
    ];

    /**
     * @var string
     */
    const KEYS_SEPARATOR = '-';

    /**
     * @const string
     */
    const PAYPAL_PAYMENT = 'ingenico_paypal';
    /**
     * @var string
     */
    const NON_GERMAN_ACCOUNT_PAYMENT = 'ingenico_sofortueberweisungDE';

    /**
     * @var string
     */
    const GERMAN_ACCOUNT_PAYMENT = 'ingenico_sofortueberweisung';
}
<?php

namespace Mediaopt\Ogone\Sdk\Model;

/*
  0     Incomplete or invalid
  1     Cancelled by client
  2     Authorization refused
  4     Order stored
  41    Waiting client payment
  5     Authorized
  51    Authorization waiting
  52    Authorization not known
  59    Author. to get manually
  6     Authorized and canceled
  61    Author. deletion waiting
  62    Author. deletion uncertain
  63    Author. deletion refused
  7     Payment deleted
  71    Payment deletion pending
  72    Payment deletion uncertain
  73    Payment deletion refused
  74    Payment deleted (not accepted)
  75    Deletion processed by merchant
  8     Refund
  81    Refund pending
  82    Refund uncertain
  83    Refund refused
  84    Payment declined by the acquirer (will be debited)
  85    Refund processed by merchant
  9     Payment requested
  91    Payment processing
  92    Payment uncertain
  93    Payment refused
  94    Refund declined by the acquirer
  95    Payment processed by merchant
  97-99 Being processed (intermediate technical status)

  The table above summarises the possible statuses of the payments.

  Statuses in 1 digit are 'normal' statuses:
  0 means the payment is invalid (e.g. data validation error) or the processing is not complete either because it is still underway, or because the transaction was interrupted. If the cause is a validation error, an additional error code (*) (NCERROR) identifies the error.
  1 means the customer cancelled the transaction.
  2 means the acquirer did not authorise the payment.
  5 means the acquirer autorised the payment.
  9 means the payment was captured.

  Statuses in 2 digits correspond either to 'intermediary' situations or to abnormal events. When the second digit is:
  1, this means the payment processing is on hold.
  2, this means an unrecoverable error occurred during the communication with the acquirer. The result is therefore not determined. You must therefore call the acquirer's helpdesk to find out the actual result of this transaction.
  3, this means the payment processing (capture or cancellation) was refused by the acquirer whilst the payment had been authorised beforehand. It can be due to a technical error or to the expiration of the authorisation. You must therefore call the acquirer's helpdesk to find out the actual result of this transaction.
  4, this means our system has been notified the transaction was rejected well after the transaction was sent to your acquirer.
  5, this means our system hasn’t sent the requested transaction to the acquirer since the merchant will send the transaction to the acquirer himself, like he specified in his configuration.

 */

/**
 * $Id: $
 */
class StatusType extends AbstractModel
{

    const INCOMPLETE_OR_INVALID = 0;
    const CANCELLED_BY_CLIENT = 1;
    const AUTHORIZATION_REFUSED = 2;
    const ORDER_STORED = 4;
    const WAITING_CLIENT_PAYMENT = 41;
    const AUTHORIZED = 5;
    const AUTHORIZATION_WAITING = 51;
    const AUTHORIZATION_NOT_KNOWN = 52;
    const AUTHOR__TO_GET_MANUALLY = 59;
    const AUTHORIZED_AND_CANCELED = 6;
    const AUTHOR__DELETION_WAITING = 61;
    const AUTHOR__DELETION_UNCERTAIN = 62;
    const AUTHOR__DELETION_REFUSED = 63;
    const PAYMENT_DELETED = 7;
    const PAYMENT_DELETION_PENDING = 71;
    const PAYMENT_DELETION_UNCERTAIN = 72;
    const PAYMENT_DELETION_REFUSED = 73;
    const PAYMENT_DELETED__NOT_ACCEPTED = 74;
    const DELETION_PROCESSED_BY_MERCHANT = 75;
    const REFUND = 8;
    const REFUND_PENDING = 81;
    const REFUND_UNCERTAIN = 82;
    const REFUND_REFUSED = 83;
    const PAYMENT_DECLINED_BY_THE_ACQUIRER__WILL_BE_DEBITED = 84;
    const REFUND_PROCESSED_BY_MERCHANT = 85;
    const PAYMENT_REQUESTED = 9;
    const PAYMENT_PROCESSING = 91;
    const PAYMENT_UNCERTAIN = 92;
    const PAYMENT_REFUSED = 93;
    const REFUND_DECLINED_BY_THE_ACQUIRER = 94;
    const PAYMENT_PROCESSED_BY_MERCHANT = 95;
    const BEING_PROCESSED__INTERMEDIATE_TECHNICAL_STATUS_1 = 97;
    const BEING_PROCESSED__INTERMEDIATE_TECHNICAL_STATUS_2 = 98;
    const BEING_PROCESSED__INTERMEDIATE_TECHNICAL_STATUS_3 = 99;
    const SHA_IN_MISMATCH = 50001184;

    static protected $statusTextByCode = array(
        0 => 'Incomplete or invalid',
        1 => 'Cancelled by client',
        2 => 'Authorization refused',
        4 => 'Order stored',
        41 => 'Waiting client payment',
        5 => 'Authorized',
        51 => 'Authorization waiting',
        52 => 'Authorization not known',
        59 => 'Author. to get manually',
        6 => 'Authorized and canceled',
        61 => 'Author. deletion waiting',
        62 => 'Author. deletion uncertain',
        63 => 'Author. deletion refused',
        7 => 'Payment deleted',
        71 => 'Payment deletion pending',
        72 => 'Payment deletion uncertain',
        73 => 'Payment deletion refused',
        74 => 'Payment deleted (not accepted)',
        75 => 'Deletion processed by merchant',
        8 => 'Refund',
        81 => 'Refund pending',
        82 => 'Refund uncertain',
        83 => 'Refund refused',
        84 => 'Payment declined by the acquirer (will be debited)',
        85 => 'Refund processed by merchant',
        9 => 'Payment requested',
        91 => 'Payment processing',
        92 => 'Payment uncertain',
        93 => 'Payment refused',
        94 => 'Refund declined by the acquirer',
        95 => 'Payment processed by merchant',
        97 => 'Being processed (intermediate technical status)',
        98 => 'Being processed (intermediate technical status)',
        99 => 'Being processed (intermediate technical status)',
        50001184 => 'SHA-IN Mismatch',
    );

    /**
     *
     * @var boolean
     */
    protected $isThankYouStatus;

    /**
     *
     * @var boolean
     */
    protected $isOkStatus;

    /**
     *
     * @var string
     */
    protected $translatedStatusMessage;

    public function getIsThankyouStatus()
    {
        return $this->isThankYouStatus;
    }

    public function getIsOkStatus()
    {
        return $this->isOkStatus;
    }

    public function getTranslatedStatusMessage()
    {
        return $this->translatedStatusMessage;
    }
    
    public static function getStatusTextByCode()
    {
        return self::$statusTextByCode;
    }

    public function setTranslatedStatusMessage($translatedStatusMessage)
    {
        $this->translatedStatusMessage = $translatedStatusMessage;
    }

    public function setIsThankyouStatus($isThankYouStatus)
    {
        $this->isThankYouStatus = $isThankYouStatus;
    }

    public function setIsOkStatus($isOkStatus)
    {
        $this->isOkStatus = $isOkStatus;
    }

}

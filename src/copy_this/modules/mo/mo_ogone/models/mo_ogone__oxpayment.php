<?php

use Mediaopt\Ogone\Sdk\Main;

/**
 * $Id: mo_ogone__oxpayment.php 9 2012-12-12 14:15:10Z martin $
 */
class mo_ogone__oxpayment extends mo_ogone__oxpayment_parent
{

    /**
     * @extend isValidPayment
     *
     * validate some ogone payment-methods
     *
     * @param array $aDynvalue
     * @param string $sShopId
     * @param oxUser $oUser
     * @param float $dBasketPrice
     * @param string $sShipSetId
     * @return bool
     */
    public function isValidPayment($aDynvalue, $sShopId, $oUser, $dBasketPrice, $sShipSetId)
    {
        $result = parent::isValidPayment($aDynvalue, $sShopId, $oUser, $dBasketPrice, $sShipSetId);

        if (!$result) {
            return $result;
        }

        if (!$this->mo_ogone__isOgonePayment()) {
            return $result;
        }

        switch ($this->getId()) {
            case 'ogone_open_invoice_de':
                return $this->mo_ogone__validateInvoice($aDynvalue);
                break;
            case 'ogone_credit_card':
                return $this->mo_ogone__validateCreditcard($aDynvalue);
                break;
            default:
                return $result;
        }
    }

    /**
     * check if given paymentid is ogone-payment
     * @return bool
     */
    public function mo_ogone__isOgonePayment()
    {
        $paymentIds = Main::getInstance()->getService('OgonePayments')->getShopPaymentIds();
        return in_array($this->getId(), $paymentIds, false);
    }

    /**
     *
     * validate & format birthdate parameter
     *
     * @param array $dynvalues
     * @return bool
     */
    protected function mo_ogone__validateInvoice($dynvalues)
    {
        if (empty($dynvalues['mo_ogone']['invoice']['birthdate'])) {
            $this->_iPaymentError = 1;
            return false;
        }

        if (!$dynvalues['mo_ogone']['invoice']['agb']) {
            $this->_iPaymentError = 1;
            return false;
        }

        $birthdate = $dynvalues['mo_ogone']['invoice']['birthdate'];

        //convert german format
        if (preg_match('#^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$#', $birthdate, $matches)) {
            $birthdate = ($matches[3] . '-' . $matches[2] . '-' . $matches[1]);
        }

        if (!preg_match('#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$#', $birthdate, $matches)) {
            $this->_iPaymentError = 1;
            return false;
        }

        //format to yyyy-mm-dd
        $birthdate = explode('-', $birthdate);
        $birthdate = $birthdate[0] . '-' . str_pad($birthdate[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($birthdate[2], 2, '0', STR_PAD_LEFT);


        //store formatted data in session
        $dynvalues['mo_ogone']['invoice']['birthdate'] = $birthdate;
        oxRegistry::getSession()->setVariable('dynvalue', $dynvalues);

        //valid date ?
        if (!strtotime($birthdate)) {
            $this->_iPaymentError = 1;
            return false;
        }

        return true;
    }

    protected function mo_ogone__validateCreditcard($dynvalues)
    {
        // only check dynvalues if hidden auth is deactivated
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_hidden_auth')) {
            return true;
        }
        $id = $this->getId();
        $brand = $dynvalues['mo_ogone']['cc']['brand'];
        $options = oxRegistry::getConfig()->getConfigParam('mo_ogone__paymentOptions');
        $option = isset($options[$id]) ? $options[$id] : array();
        return in_array($brand, $option, false);

    }

}

<?php

use Mediaopt\Ingenico\Sdk\Main;

/**
 * $Id: mo_ingenico__oxpayment.php 9 2012-12-12 14:15:10Z martin $
 */
class mo_ingenico__oxpayment extends mo_ingenico__oxpayment_parent
{

    /**
     * @extend isValidPayment
     *
     * validate some ingenico payment-methods
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

        if (!$this->mo_ingenico__isIngenicoPayment()) {
            return $result;
        }

        switch ($this->getId()) {
            case 'ingenico_open_invoice_de':
                return $this->mo_ingenico__validateInvoice($aDynvalue);
                break;
            case 'ingenico_credit_card':
                return $this->mo_ingenico__validateCreditcard($aDynvalue);
                break;
            default:
                return $result;
        }
    }

    /**
     * check if given paymentid is ingenico-payment
     * @return bool
     */
    public function mo_ingenico__isIngenicoPayment()
    {
        $paymentIds = Main::getInstance()->getService('IngenicoPayments')->getShopPaymentIds();
        return in_array($this->getId(), $paymentIds, false);
    }

    /**
     *
     * validate & format birthdate parameter
     *
     * @param array $dynvalues
     * @return bool
     */
    protected function mo_ingenico__validateInvoice($dynvalues)
    {
        if (empty($dynvalues['mo_ingenico']['invoice']['birthdate'])) {
            $this->_iPaymentError = 1;
            return false;
        }

        if (!$dynvalues['mo_ingenico']['invoice']['agb']) {
            $this->_iPaymentError = 1;
            return false;
        }

        $birthdate = $dynvalues['mo_ingenico']['invoice']['birthdate'];

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
        $dynvalues['mo_ingenico']['invoice']['birthdate'] = $birthdate;
        oxRegistry::getSession()->setVariable('dynvalue', $dynvalues);

        //valid date ?
        if (!strtotime($birthdate)) {
            $this->_iPaymentError = 1;
            return false;
        }

        return true;
    }

    protected function mo_ingenico__validateCreditcard($dynvalues)
    {
        // only check dynvalues if hidden auth is deactivated
        if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_hidden_auth')) {
            return true;
        }
        $id = $this->getId();
        $brand = $dynvalues['mo_ingenico']['cc']['brand'];
        $options = oxRegistry::getConfig()->getConfigParam('mo_ingenico__paymentOptions');
        $option = isset($options[$id]) ? $options[$id] : array();
        return in_array($brand, $option, false);

    }

}

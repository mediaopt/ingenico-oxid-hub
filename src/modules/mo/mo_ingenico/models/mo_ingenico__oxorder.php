<?php

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Service\Status;

/**
 * This file is part of Ingenico Payment Solutions payment interface
 *
 * Ingenico Payment Solutions payment interface is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ingenico Payment Solutions payment interface is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ingenico Payment Solutions payment interface.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @link http://www.ingenico.com
 * @package modules
 * @copyright (C) Ingenico 2009, 2010
 * @version 1.3
 * $Id: mo_ingenico__oxorder.php 56 2014-12-01 10:36:13Z martin $
 */
class mo_ingenico__oxorder extends mo_ingenico__oxorder_parent
{

    const MO_INGENICO__TRANSACTION_ID_PENDING = 'MO_INGENICO_PENDING';
    const MO_INGENICO__TRANSACTION_ID_DONE = 'MO_INGENICO_DONE';

    public function mo_ingenico__initBeforePayment($oUser, $oBasket)
    {
        // validating various order/basket parameters before finalizing
        if ($iOrderState = $this->validateOrder($oBasket, $oUser)) {
            if (!$iOrderState === oxOrder::ORDER_STATE_INVALIDDElADDRESSCHANGED) {
                return $iOrderState;
            }
        }
        
        $this->setId(oxRegistry::getSession()->getVariable('sess_challenge'));
        // copies user info
        $this->_setUser($oUser);

        // copies basket info
        $this->_loadFromBasket($oBasket);

        // payment information
        //@todo check if still needed
        $oUserPayment = $this->_setPayment($oBasket->getPaymentId());

        // set folder information, if order is new
        // #M575 in recalculating order case folder must be the same as it was
        if (!$blRecalculatingOrder) {
            $this->_setFolder();
        }

        if (!$this->oxorder__oxordernr->value) {
            $this->_setNumber();
        } else {
            oxNew('oxCounter')->update($this->_getCounterIdent(), $this->oxorder__oxordernr->value);
        }
        
        // marking as not finished
        $this->_setOrderStatus('NOT_FINISHED');
        $this->oxorder__oxtransstatus = new oxField('ERROR');
    }

    public function mo_ingenico__isIngenicoOrder()
    {
        return false !== stristr($this->oxorder__oxpaymenttype->value, 'ingenico');
    }

    public function mo_ingenico__isPaymentDone()
    {
        /* @var $status Status */
        $status = Main::getInstance()->getService('Status')
                ->usingStatusCode($this->oxorder__mo_ingenico__status->value);
        return $status->isThankyouStatus();
    }

    public function mo_ingenico__loadByNumber($orderNr)
    {
        $sSelect = $this->buildSelectString(array($this->getViewName() . '.oxtransid' => $orderNr));
        return $this->_isLoaded = $this->assignRecord($sSelect);
    }

    /**
     * Updates order transaction id.
     * @todo add try catch to db call
     *
     * @param string $orderID order transaction id
     * @throws \oxConnectionException
     */
    public function mo_ingenico__setTransID($orderID)
    {
        $oDb = oxDb::getDb();
        $sQ = 'update oxorder set oxtransid=' . $oDb->quote($orderID) . ' where oxid=' . $oDb->quote($this->getId());
        $oDb->execute($sQ);
        //updating order object
        $this->oxorder__oxtransid = new oxField($orderID, oxField::T_RAW);
    }

    /**
     * Updates oxtransstatus and mo_ingenico__status property on order object and save the order object
     * if the order is cancelled by client the order will canceled (storno)
     * @param $ingenicoStatus
     */
    public function mo_ingenico__updateOrderStatus($ingenicoStatus)
    {
        if ($ingenicoStatus->isOkStatus() || $this->_checkOrderExist($this->getId())){
            $oxidStatus = $ingenicoStatus->isOkStatus() ? 'OK' : 'ERROR';
            if ($ingenicoStatus->isOkStatus() && oxRegistry::getConfig()->getShopConfVar('mo_ingenico__set_oxpaid')) {
                $sDate = date('Y-m-d H:i:s', oxRegistry::get('oxUtilsDate')->getTime());
                $this->oxorder__oxpaid = new oxField($sDate, oxField::T_RAW);
            }
            $this->oxorder__oxtransstatus = new oxField($oxidStatus);
            $this->oxorder__mo_ingenico__status = new oxField($ingenicoStatus->getStatusCode());

            $activeView = oxRegistry::getConfig()->getActiveView()->getClassName();
            $activeViewFnc = oxRegistry::getConfig()->getActiveView()->getFncName();
            if ($activeView === 'order' && $activeViewFnc !== 'mo_ingenico__fncHandleDeferredFeedback' && $ingenicoStatus->isThankyouStatus()) {
                $this->mo_ingenico__sendEmails();
            }
            if ($ingenicoStatus->isStornoStatus()) {
                $this->cancelOrder();
            }

            $this->save();
        }
    }

    /**
     * @overload
     * @param $sMaxField
     * @param null $aWhere
     * @param int $iMaxTryCnt
     */
    protected function _setRecordNumber($sMaxField, $aWhere = null, $iMaxTryCnt = 5)
    {
        $orderNumberReservation = oxNew('mo_ingenico__order_number_reservation');
        do {
            // as long as a reservation exists for the current order number 
            // create a new order number
            parent::_setRecordNumber($sMaxField, $aWhere, $iMaxTryCnt);

            $reservationExists = $orderNumberReservation
                    ->load(mo_ingenico__order_number_reservation::getReservationKey($this->oxorder__oxordernr->value));
        } while ($reservationExists);
    }

    /**
     * Send order to shop owner and user
     *
     * @param oxUser        $oUser    order user
     * @param oxBasket      $oBasket  current order basket
     * @param oxUserPayment $oPayment order payment
     *
     * @return bool
     */
    protected function _sendOrderByEmail($oUser = null, $oBasket = null, $oPayment = null)
    {
        // Mail will be sent using mo_ingenico__sendEmails and checked using variable mo_ingenico__mailError
        if ($this->mo_ingenico__isIngenicoOrder()) {
            return self::ORDER_STATE_OK;
        }
        return parent::_sendOrderByEmail($oUser, $oBasket, $oPayment);
    }

    public function mo_ingenico__sendEmails()
    {
        if ($this->mo_ingenico__isIngenicoOrder() && $this->mo_ingenico__isPaymentDone()) {
            $this->_oBasket = oxRegistry::getSession()->getBasket();
            $this->_oUser = oxRegistry::getSession()->getUser();
            $this->_oPayment = $this->getPaymentType();
            $emailSent = parent::_sendOrderByEmail($this->_oUser, $this->_oBasket, $this->_oPayment);

            if ($emailSent) {
                oxRegistry::getSession()->deleteVariable('mo_ingenico__mailError');
            }
        }
    }

    public function mo_ingenico__getStatusText()
    {
        return \Mediaopt\Ingenico\Sdk\Main::getInstance()->getService('Status')->usingStatusCode($this->oxorder__mo_ingenico__status->value)->getTranslatedStatusMessage();
    }
}

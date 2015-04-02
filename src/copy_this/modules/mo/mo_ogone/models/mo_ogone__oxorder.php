<?php

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Service\Status;

/**
 * This file is part of Ogone Payment Solutions payment interface
 *
 * Ogone Payment Solutions payment interface is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ogone Payment Solutions payment interface is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ogone Payment Solutions payment interface.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @link http://www.ogone.com
 * @package modules
 * @copyright (C) Ogone 2009, 2010
 * @version 1.3
 * $Id: mo_ogone__oxorder.php 56 2014-12-01 10:36:13Z martin $
 */
class mo_ogone__oxorder extends mo_ogone__oxorder_parent
{

    const MO_OGONE__TRANSACTION_ID_PENDING = 'MO_OGONE_PENDING';
    const MO_OGONE__TRANSACTION_ID_DONE = 'MO_OGONE_DONE';

    public function mo_ogone__initBeforePayment($oUser, $oBasket)
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

    public function mo_ogone__isOgoneOrder()
    {
        return stristr($this->oxorder__oxpaymenttype->value, 'ogone') !== false;
    }

    public function mo_ogone__isPaymentDone()
    {
        /* @var $status Status */
        $status = Main::getInstance()->getService("Status")
                ->usingStatusCode($this->oxorder__mo_ogone__status->value);
        return $status->isThankyouStatus();
    }

    public function mo_ogone__loadByNumber($orderNr)
    {
        $sSelect = $this->buildSelectString(array($this->getViewName() . '.oxtransid' => $orderNr));
        return $this->_isLoaded = $this->assignRecord($sSelect);
    }
    
     /**
     * Updates order transaction id.
     *
     * @param type $orderID order transaction id
     */
    public function mo_ogone__setTransID($orderID)
    {
        $oDb = oxDb::getDb();
        $sQ = 'update oxorder set oxtransid=' . $oDb->quote($orderID) . ' where oxid=' . $oDb->quote($this->getId());
        $oDb->execute($sQ);
        //updating order object
        $this->oxorder__oxtransid = new oxField($orderID, oxField::T_RAW);
    }

    /**
     * Updates oxtransstatus and mo_ogone__status property on order object and save the order object
     * if the order is cancelled by client the order will canceled (storno)
     */
    public function mo_ogone__updateOrderStatus($ogoneStatus)
    {
        if ($ogoneStatus->isOkStatus() || $this->_checkOrderExist($this->getId())){
            $oxidStatus = $ogoneStatus->isOkStatus() ? 'OK' : 'ERROR';
            if ($ogoneStatus->isOkStatus() && oxRegistry::getConfig()->getShopConfVar('mo_ogone__set_oxpaid')) {
                $sDate = date('Y-m-d H:i:s', oxRegistry::get("oxUtilsDate")->getTime());
                $this->oxorder__oxpaid = new oxField($sDate, oxField::T_RAW);
            }
            $this->oxorder__oxtransstatus = new oxField($oxidStatus);
            $this->oxorder__mo_ogone__status = new oxField($ogoneStatus->getStatusCode());

            $activeView = oxRegistry::getConfig()->getActiveView()->getClassName();
            if ($ogoneStatus->isThankyouStatus() && $activeView == 'order') {
                $this->mo_ogone__sendEmails();
            }
            if ($ogoneStatus->isStornoStatus()) {
                $this->cancelOrder();
            }

            $this->save();
        }
    }

    /**
     * @overload
     */
    protected function _setRecordNumber($sMaxField, $aWhere = null, $iMaxTryCnt = 5)
    {
        $orderNumberReservation = oxNew('mo_ogone__order_number_reservation');
        do {
            // as long as a reservation exists for the current order number 
            // create a new order number
            parent::_setRecordNumber($sMaxField, $aWhere, $iMaxTryCnt);

            $reservationExists = $orderNumberReservation
                    ->load(mo_ogone__order_number_reservation::getReservationKey($this->oxorder__oxordernr->value));
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
        // Mail will be sent using mo_ogone__sendEmails and checked using variable mo_ogone__mailError
        if ($this->mo_ogone__isOgoneOrder()) {
            return self::ORDER_STATE_OK;
        }
        return parent::_sendOrderByEmail($oUser, $oBasket, $oPayment);
    }

    public function mo_ogone__sendEmails()
    {
        if ($this->mo_ogone__isOgoneOrder() && $this->mo_ogone__isPaymentDone()) {
            $this->_oBasket = oxRegistry::getSession()->getBasket();
            $this->_oUser = oxRegistry::getSession()->getUser();
            $this->_oPayment = $this->getPaymentType();
            $emailSent = parent::_sendOrderByEmail($this->_oUser, $this->_oBasket, $this->_oPayment);

            if ($emailSent) {
                oxRegistry::getSession()->deleteVariable('mo_ogone__mailError');
            }
        }
    }

}

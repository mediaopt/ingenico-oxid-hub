<?php

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
  const MO_OGONE__TRANSACTION_ID_DONE    = 'MO_OGONE_DONE';

  public function mo_ogone__initBeforePayment()
  {
    $this->oxorder__oxtransstatus = new oxField('ERROR');
    $this->save();
  }

  public function mo_ogone__isOgoneOrder()
  {
    return stristr($this->oxorder__oxpaymenttype->value, 'ogone') !== false;
  }

  public function mo_ogone__isPaymentDone()
  {
    return mo_ogone__status::isOxidThankyouStatus($this->oxorder__mo_ogone__status->value);
  }

  public function mo_ogone__loadByNumber($orderNr)
  {
    $sSelect = $this->buildSelectString(array($this->getViewName() . '.oxordernr' => $orderNr));
    return $this->_isLoaded = $this->assignRecord($sSelect);
  }

  /**
   * Updates oxtransstatus and mo_ogone__status property on order object and save the order object
   * if the order is cancelled by client the order will canceled (storno)
   */
  public function mo_ogone__updateOrderStatus($ogoneStatus)
  {
    $oxidStatus = mo_ogone__status::isOxidOkStatus($ogoneStatus) ? 'OK' : 'ERROR';
    $this->oxorder__oxtransstatus = new oxField($oxidStatus);
    $this->oxorder__mo_ogone__status = new oxField($ogoneStatus);

    $activeView = oxRegistry::getConfig()->getActiveView()->getClassName();
    if (mo_ogone__status::isOxidThankyouStatus($ogoneStatus) && $activeView == 'order')
    {
      $this->mo_ogone__sendEmails();      
    }


    if (mo_ogone__status::isStornoStatus($ogoneStatus))
    {
      $this->cancelOrder();
    }

    $this->save();
  }

  /**
   * @overload
   */
  protected function _setRecordNumber($sMaxField, $aWhere = null, $iMaxTryCnt = 5)
  {
    $orderNumberReservation = oxNew('mo_ogone__order_number_reservation');
    do
    {
      // as long as a reservation exists for the current order number 
      // create a new order number
      parent::_setRecordNumber($sMaxField, $aWhere, $iMaxTryCnt);

      $reservationExists =
              $orderNumberReservation->load(mo_ogone__order_number_reservation::getReservationKey($this->oxorder__oxordernr->value));
    }
    while ($reservationExists);
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
    if ($this->mo_ogone__isOgoneOrder() && !$this->mo_ogone__isPaymentDone())
    {
      return self::ORDER_STATE_MAILINGERROR;
    }

    return parent::_sendOrderByEmail($oUser, $oBasket, $oPayment);
  }

  public function mo_ogone__sendEmails()
  {
    $this->_oBasket = oxRegistry::getSession()->getBasket();
    $this->_oUser = oxRegistry::getSession()->getUser();
    $this->_oPayment = $this->getPaymentType();

    $emailSent = $this->_sendOrderByEmail($this->_oUser, $this->_oBasket, $this->_oPayment);

    if ($emailSent)
    {
      oxRegistry::getSession()->deleteVariable('mo_ogone__mailError');
    }
  }

}

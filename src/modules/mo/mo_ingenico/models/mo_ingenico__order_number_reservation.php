<?php

/**
 * $Id: mo_ingenico__order_number_reservation.php 6 2012-12-12 10:16:57Z martin $ 
 */
class mo_ingenico__order_number_reservation extends oxBase
{

  /**
   * Object core table name
   *
   * @var string
   */
  protected $_sCoreTable = 'mo_ingenico__order_number_reservations';

  /**
   * Current class name
   *
   * @var string
   */
  protected $_sClassName = 'mo_ingenico__order_number_reservation';

  public static function getReservationKey($orderNr)
  {
    if (oxRegistry::getConfig()->getConfigParam('blSeparateNumbering'))
    {
      return $orderNr . '-' . oxRegistry::getConfig()->getShopId();
    }
    return $orderNr;
  }

}

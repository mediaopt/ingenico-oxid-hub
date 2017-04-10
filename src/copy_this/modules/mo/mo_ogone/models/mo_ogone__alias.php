<?php
/**
 * For the full copyright and license information, refer to the accompanying LICENSE file.
 *
 * @copyright 2017 derksen mediaopt GmbH
 */

namespace models;

/**
 * model to store credit card alias data
 *
 * @author derksen mediaopt GmbH
 * @package models
 */
class mo_ogone__alias extends oxBase
{

  /**
   * Object core table name
   *
   * @var string
   */
  protected $_sCoreTable = 'mo_ogone__alias';

  /**
   * Current class name
   *
   * @var string
   */
  protected $_sClassName = 'mo_ogone__alias';

  public function getExpirationData()
  {
      $expDate = $this->mo_ogone__alias__exp_date->value;
      $month = substr($expDate, 5,2);
      $year = substr($expDate, 2,2);
      return $month.'/'.$year;
  }

}

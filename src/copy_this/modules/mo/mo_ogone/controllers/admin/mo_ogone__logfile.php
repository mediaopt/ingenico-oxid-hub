<?php

use Mediaopt\Ogone\Sdk\Main;

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
 * @package admin
 * @copyright (C) Ogone 2009, 2010
 * @version 1.3
 * $Id: mo_ogone__logfile.php 53 2014-11-28 13:54:26Z martin $
 */

/**
 * Modul Logfile
 *
 * Admin Menu: Shop Config -> Ogone -> Log.
 * @package admin
 */
class mo_ogone__logfile extends oxAdminView
{

  /**
   * Current Version String.
   * @var string
   */
  protected $_sVersion      = 'Ogone Payment Module Version: 2.2.1';

  /**
   * Current class template.
   * @var string
   */
  protected $_sThisTemplate = 'mo_ogone__logfile.tpl';

  public function render()
  {
    $myConfig = $this->getConfig();

    parent::render();

    $sCurrentAdminShop = oxRegistry::getSession()->getVariable("currentadminshop");

    if (!$sCurrentAdminShop)
    {
      if (oxRegistry::getSession()->getVariable("malladmin"))
        $sCurrentAdminShop = "oxbaseshop";
      else
        $sCurrentAdminShop = oxRegistry::getSession()->getVariable("actshop");
    }

    $LogFile = Main::getInstance()->getAdapter()->getLogFilePath();

    $fLog = fopen($LogFile, "r");
    while (!feof($fLog))
    {
      $sLog .= trim(fgets($fLog, 4096)) . "\n";
    }
    fclose($fLog);

    $this->_aViewData["logfile"] = $sLog;
    $this->_aViewData["currentadminshop"] = $sCurrentAdminShop;
    oxRegistry::getSession()->setVariable("currentadminshop", $sCurrentAdminShop);

    if (stristr($this->getConfig()->getConfigParam('mo_ogone__gateway_url_redirect'), 'prod'))
      $this->_aViewData['bottom_buttons'] = 'prod';
    else
      $this->_aViewData['bottom_buttons'] = 'test';

    $this->_aViewData['version'] = $this->_sVersion;

    return $this->_sThisTemplate;
  }

}
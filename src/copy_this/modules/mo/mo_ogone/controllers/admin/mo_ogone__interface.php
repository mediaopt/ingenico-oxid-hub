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
 * @package admin
 * @copyright (C) Ogone 2009, 2010
 * @version 1.3
 * $Id: mo_ogone__interface.php 53 2014-11-28 13:54:26Z martin $
 */

/**
 * Modul Interface for Transaction Statuses

 * Admin Menu: Shop Config -> Ogone -> Status.
 * @package admin
 */
class mo_ogone__interface extends oxAdminList
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
  protected $_sThisTemplate = "mo_ogone__interface.tpl";
  var $iAdminListSize = 50;

  function Init()
  {
    $myConfig = $this->getConfig();

    $myConfig->iAdminListSize = $this->iAdminListSize;
    $ret = parent::Init();

    $this->_setCurrentListPosition(oxRegistry::getConfig()->getRequestParameter('jumppage'));

    return $ret;
  }

  function render()
  {
    $sReturn = parent::render();


    $myConfig = $this->getConfig();

    $this->oList = oxNew("oxlist");
    $this->oList->Init("oxbase", "mo_ogone__payment_logs");

    $sSelect = "select *, concat(billlname, ', ', billfname) as customer_name from mo_ogone__payment_logs ";

    $sCountSelect = "select count(*) from mo_ogone__payment_logs ";

    $sWhere = '';

    if ($this->blfiltering)
    {
      $aFilter = $myConfig->getRequestParameter("ogonelogfilter");
      if (is_array($aFilter))
      {
        foreach ($aFilter as $sKey => $sValue)
        {
          if ($sValue != '')
          {
            if ($sWhere)
            {
              $sWhere .= ' and ';
            }
            else
            {
              $sWhere .= ' where ';
            }

            if ($sKey == "customer_name")
            {
              $sWhere .= "(";
              $sWhere .= "mo_ogone__payment_logs.billfname like '%" . $sValue . "%' ";
              $sWhere .= " or ";
              $sWhere .= "mo_ogone__payment_logs.billfname like '%" . $sValue . "%' ";
              $sWhere .= ")";
            }
            elseif ($sKey == "acceptance" or $sKey == "cn")
            {
              $sWhere = "mo_ogone__payment_logs.$sKey like '%" . $sValue . "%' ";
            }
            elseif ($sKey == "orderid")
            {
              $sWhere = "mo_ogone__payment_logs.$sKey like '" . $sValue . "%' ";
            }
            else
            {
              $sWhere = "where mo_ogone__payment_logs.$sKey = '" . $sValue . "' ";
            }

            $this->_aViewData['ogonelogfilter'][$sKey] = $sValue;
          }
        }
      }
    }

    $sSelect .= $sWhere;
    $sCountSelect .= $sWhere;

    $this->iListSize = oxDb::getDb()->getOne($sCountSelect);
    if (!$this->iCurrListPos)
      $this->iCurrListPos = 1;

    $iAdminListSize = $myConfig->getConfigParam('iAdminListSize');

    $this->_setCurrentListPosition(oxRegistry::getConfig()->getRequestParameter('jumppage'));
    $sSelect .= "order by mo_ogone__payment_logs.date desc";
    $this->oList->iSQLRecords = $myConfig->getConfigParam('iAdminListSize');
    $this->oList->iSQLStart = $this->iCurrListPos;

    $this->oList->setSqlLimit($this->iCurrListPos, $myConfig->getConfigParam('iAdminListSize'));

    if ($this->oList->iSQLStart > $this->iListSize)
      $this->oList->iSQLStart = 0;

    $this->oList->selectString($sSelect);

    $this->_aViewData['aLogList'] = $this->oList->aList;
    $this->_aViewData['iCount'] = $this->oList->iSQLStart;

    if ($this->iListSize > $iAdminListSize)
    {
      $pagenavigation = new oxStdClass();
      $pagenavigation->pages = round(( ( $this->iListSize - 1 ) / $iAdminListSize ) + 0.5, 0);
      $pagenavigation->actpage = ($pagenavigation->actpage > $pagenavigation->pages) ? $pagenavigation->pages : round(( $this->iCurrListPos / $iAdminListSize ) + 0.5, 0);
      $pagenavigation->lastlink = ( $pagenavigation->pages - 1 ) * $iAdminListSize;
      $pagenavigation->nextlink = null;
      $pagenavigation->backlink = null;

      $iPos = $this->iCurrListPos + $iAdminListSize;
      if ($iPos < $this->iListSize)
        $pagenavigation->nextlink = $iPos = $this->iCurrListPos + $iAdminListSize;

      if (( $this->iCurrListPos - $iAdminListSize ) >= 0)
        $pagenavigation->backlink = $iPos = $this->iCurrListPos - $iAdminListSize;

      // calculating list start position
      $iStart = $pagenavigation->actpage - 5;
      $iStart = ( $iStart <= 0 ) ? 1 : $iStart;

      // calculating list end position
      $iEnd = $pagenavigation->actpage + 5;
      $iEnd = ( $iEnd < $iStart + 10) ? $iStart + 10 : $iEnd;
      $iEnd = ( $iEnd > $pagenavigation->pages ) ? $pagenavigation->pages : $iEnd;

      // once again adjusting start pos ..
      $iStart = ( $iEnd - 10 > 0 ) ? $iEnd - 10 : $iStart;
      $iStart = ( $pagenavigation->pages <= 11) ? 1 : $iStart;

      // navigation urls
      for ($i = $iStart; $i <= $iEnd; $i++)
      {
        $page = new Oxstdclass();
        $page->selected = 0;
        if ($i == $pagenavigation->actpage)
          $page->selected = 1;

        $pagenavigation->changePage[$i] = $page;
      }

      $this->_aViewData['pagenavi'] = $pagenavigation;

      if (isset($this->iOverPos))
      {
        $iPos = $this->iOverPos;
        $this->iOverPos = null;
      }
      else
        $iPos = oxRegistry::getConfig()->getRequestParameter('lstrt');

      if (!$iPos)
        $iPos = 0;

      $this->_aViewData['lstrt'] = $iPos;
      $this->_aViewData['listsize'] = $this->iListSize;
      $blShowNavigation = true;
    }

    // hidden default bottom custom navi
    $this->_aViewData['sHelpURL'] = false;
    $this->_aViewData['oxid'] = $myConfig->getShopId();

    if (stristr($this->getConfig()->getConfigParam('mo_ogone__gateway_url_redirect'), 'prod'))
      $this->_aViewData['bottom_buttons'] = 'prod';
    else
      $this->_aViewData['bottom_buttons'] = 'test';

    $this->_aViewData['version'] = $this->_sVersion;

    return $sReturn;
  }

  function setFilter()
  {
    $this->blfiltering = true;
  }

  /**
   * Set current list position
   *
   * @param string $sPage jump page string
   *
   * @return null
   */
  protected function _setCurrentListPosition($sPage = null)
  {
    $iAdminListSize = (int) $this->getConfig()->getConfigParam('iAdminListSize');

    $iJumpTo = $sPage ? ( (int) $sPage) : ( (int) ( (int) oxRegistry::getConfig()->getRequestParameter('lstrt') ) / $iAdminListSize );
    $iJumpTo = ( $sPage && $iJumpTo ) ? ( $iJumpTo - 1 ) : $iJumpTo;

    $iJumpTo = $iJumpTo * $iAdminListSize;
    if ($iJumpTo < 1)
      $iJumpTo = 0;
    elseif ($iJumpTo >= $this->iListSize)
      $iJumpTo = floor($this->iListSize / $iAdminListSize - 1) * $iAdminListSize;

    $this->iCurrListPos = $this->iOverPos = (int) $iJumpTo;
  }

}

?>
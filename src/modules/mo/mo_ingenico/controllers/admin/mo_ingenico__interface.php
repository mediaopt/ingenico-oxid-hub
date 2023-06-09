<?php

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
 * @package admin
 * @copyright (C) Ingenico 2009, 2010
 * @version 1.3
 * $Id: mo_ingenico__interface.php 53 2014-11-28 13:54:26Z martin $
 */

/**
 * Modul Interface for Transaction Statuses
 * Admin Menu: Shop Config -> Ingenico -> Status.
 * @package admin
 */
class mo_ingenico__interface extends oxAdminList
{

    /**
     * Current class template.
     * @var string
     */
    protected $_sThisTemplate = 'mo_ingenico__interface.tpl';

    /**
     * number of displayed log entries
     *
     * @var int
     */
    protected $iAdminListSize = 50;

    /**
     * prepare log list parameters
     *
     * @extends init
     */
    public function init()
    {
        $oxConfig = $this->getConfig();
        $oxConfig->iAdminListSize = $this->iAdminListSize;

        $this->_setCurrentListPosition(oxRegistry::getConfig()->getRequestParameter('jumppage'));

        return parent::init();
    }

    /**
     * @return null
     */
    public function render()
    {
        $sReturn = parent::render();

        $oxConfig = $this->getConfig();

        $this->oList = oxNew('oxlist');
        $this->oList->init('oxbase', 'mo_ingenico__payment_logs');

        $select = "select *, concat(billfname, ' ', billlname) as customer_name from mo_ingenico__payment_logs ";

        $countSelect = 'select count(*) from mo_ingenico__payment_logs ';

        $groupByStatement = '';
        if($oxConfig->getConfigParam('mo_ingenico__useGroupBy')) {
            $groupByStatement = ' GROUP BY CONCAT(transID, "-", STATUS) ';
        }

        $conditions = [];

        if ($this->blfiltering) {
            $aFilter = $oxConfig->getRequestParameter('ingenicologfilter');
            if (is_array($aFilter)) {
                foreach ($aFilter as $sKey => $sValue) {
                    if ($sValue !== '') {


                        if ($sKey === 'customer_name') {
                            $conditions[] = "(
                                mo_ingenico__payment_logs.billfname like '%" . $sValue . "%'
                                OR mo_ingenico__payment_logs.billlname like '%" . $sValue . "%')";
                        } elseif (in_array($sKey, ['acceptance', 'cn', 'transid','payid','ncerror'])) {
                            $conditions[] = "mo_ingenico__payment_logs.$sKey like '%" . $sValue . "%' ";
                        } elseif ($sKey === 'orderid') {
                            $conditions[] = "mo_ingenico__payment_logs.$sKey like '" . $sValue . "%' ";
                        } else {
                            $conditions[] = "mo_ingenico__payment_logs.$sKey = '" . $sValue . '\' ';
                        }

                        $this->_aViewData['ingenicologfilter'][$sKey] = $sValue;
                    }
                }
            }
        }

        if (oxRegistry::getConfig()->getShopId() !== oxRegistry::getConfig()->getBaseShopId()) {
            $conditions[] = 'SHOPID = "'.oxRegistry::getConfig()->getShopId().'"';
        }
        if ($conditions) {
            $whereStatement = ' where '.implode(' and ', $conditions);
            $select .= $whereStatement;
            $countSelect .= $whereStatement;
        }
        $select .= $groupByStatement;

        $this->iListSize = oxDb::getDb()->getOne($countSelect);
        if (!$this->iCurrListPos) {
            $this->iCurrListPos = 1;
        }

        $iAdminListSize = $oxConfig->getConfigParam('iAdminListSize');

        $this->_setCurrentListPosition(oxRegistry::getConfig()->getRequestParameter('jumppage'));
        $select .= 'order by mo_ingenico__payment_logs.date desc';
        $this->oList->iSQLRecords = $oxConfig->getConfigParam('iAdminListSize');
        $this->oList->iSQLStart = $this->iCurrListPos;

        $this->oList->setSqlLimit($this->iCurrListPos, $oxConfig->getConfigParam('iAdminListSize'));

        if ($this->oList->iSQLStart > $this->iListSize) {
            $this->oList->iSQLStart = 0;
        }

        $this->oList->selectString($select);

        $this->_aViewData['aLogList'] = $this->oList->aList;
        $this->_aViewData['iCount'] = $this->oList->iSQLStart;

        if ($this->iListSize > $iAdminListSize) {
            $pagenavigation = new stdClass();
            $pagenavigation->pages = round((($this->iListSize - 1) / $iAdminListSize) + 0.5, 0);
            $pagenavigation->actpage = ($pagenavigation->actpage > $pagenavigation->pages) ? $pagenavigation->pages : round(($this->iCurrListPos / $iAdminListSize) + 0.5, 0);
            $pagenavigation->lastlink = ($pagenavigation->pages - 1) * $iAdminListSize;
            $pagenavigation->nextlink = null;
            $pagenavigation->backlink = null;

            $iPos = $this->iCurrListPos + $iAdminListSize;
            if ($iPos < $this->iListSize) {
                $pagenavigation->nextlink = $iPos = $this->iCurrListPos + $iAdminListSize;
            }

            if (($this->iCurrListPos - $iAdminListSize) >= 0) {
                $pagenavigation->backlink = $iPos = $this->iCurrListPos - $iAdminListSize;
            }

            // calculating list start position
            $iStart = $pagenavigation->actpage - 5;
            $iStart = ($iStart <= 0) ? 1 : $iStart;

            // calculating list end position
            $iEnd = $pagenavigation->actpage + 5;
            $iEnd = ($iEnd < $iStart + 10) ? $iStart + 10 : $iEnd;
            $iEnd = ($iEnd > $pagenavigation->pages) ? $pagenavigation->pages : $iEnd;

            // once again adjusting start pos ..
            $iStart = ($iEnd - 10 > 0) ? $iEnd - 10 : $iStart;
            $iStart = ($pagenavigation->pages <= 11) ? 1 : $iStart;

            // navigation urls
            for ($i = $iStart; $i <= $iEnd; $i++) {
                $page = new stdClass();
                $page->selected = 0;
                if ($i === $pagenavigation->actpage) {
                    $page->selected = 1;
                }

                $pagenavigation->changePage[$i] = $page;
            }

            $this->_aViewData['pagenavi'] = $pagenavigation;

            if (isset($this->iOverPos)) {
                $iPos = $this->iOverPos;
                $this->iOverPos = null;
            } else {
                $iPos = oxRegistry::getConfig()->getRequestParameter('lstrt');
            }

            if (!$iPos) {
                $iPos = 0;
            }

            $this->_aViewData['lstrt'] = $iPos;
            $this->_aViewData['listsize'] = $this->iListSize;
        }

        // hidden default bottom custom navi
        $this->_aViewData['sHelpURL'] = false;
        $this->_aViewData['oxid'] = $oxConfig->getShopId();

        if ($this->getConfig()->getConfigParam('mo_ingenico__isLiveMode')) {
            $this->_aViewData['bottom_buttons'] = 'prod';
        } else {
            $this->_aViewData['bottom_buttons'] = 'test';
        }

        $this->_aViewData['version'] = $this->_sVersion;

        return $sReturn;
    }

    /**
     * enable filter
     */
    public function setFilter()
    {
        $this->blfiltering = true;
    }

    /**
     * Set current list position
     *
     * @param string $sPage jump page string
     */
    protected function _setCurrentListPosition($sPage = null)
    {
        $iAdminListSize = (int)$this->getConfig()->getConfigParam('iAdminListSize');

        $iJumpTo = $sPage ? ((int)$sPage) : ((int)((int)oxRegistry::getConfig()->getRequestParameter('lstrt')) / $iAdminListSize);
        $iJumpTo = ($sPage && $iJumpTo) ? ($iJumpTo - 1) : $iJumpTo;

        $iJumpTo *= $iAdminListSize;
        if ($iJumpTo < 1) {
            $iJumpTo = 0;
        } elseif ($iJumpTo >= $this->iListSize) {
            $iJumpTo = floor($this->iListSize / $iAdminListSize - 1) * $iAdminListSize;
        }

        $this->iCurrListPos = $this->iOverPos = (int)$iJumpTo;
    }

}

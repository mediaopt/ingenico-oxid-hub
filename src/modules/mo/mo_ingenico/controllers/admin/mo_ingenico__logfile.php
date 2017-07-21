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
 * $Id: mo_ingenico__logfile.php 53 2014-11-28 13:54:26Z martin $
 */

/**
 * Modul Logfile
 *
 * Admin Menu: Shop Config -> Ingenico -> Log.
 * @package admin
 */
class mo_ingenico__logfile extends oxAdminView
{

    protected $blfiltering = false;

    /**
     * Current class template.
     * @var string
     */
    protected $_sThisTemplate = 'mo_ingenico__logfile.tpl';

    public function render()
    {
        parent::render();

        $sCurrentAdminShop = oxRegistry::getSession()->getVariable('currentadminshop');

        if (!$sCurrentAdminShop) {
            if (oxRegistry::getSession()->getVariable('malladmin')) {
                $sCurrentAdminShop = 'oxbaseshop';
            } else {
                $sCurrentAdminShop = oxRegistry::getSession()->getVariable('actshop');
            }
        }

        $LogFile = oxNew('mo_ingenico__helper')->getLogFilePath();
        $filters = [];
        if ($this->blfiltering) {
            $aFilter = $this->getConfig()->getRequestParameter('ingenicologfilter');
            $this->_aViewData['ingenicologfilter'] = $aFilter;
            if (is_array($aFilter)) {
                $filters = array_filter($aFilter);
            }
        }

        $reader = oxNew('mo_ingenico__log_parser');
        $data = $reader->parseLogFile($LogFile, $filters);

        $this->_aViewData['logfile'] = $data;
        $this->_aViewData['currentadminshop'] = $sCurrentAdminShop;
        oxRegistry::getSession()->setVariable('currentadminshop', $sCurrentAdminShop);

        if ($this->getConfig()->getConfigParam('mo_ingenico__isLiveMode')) {
            $this->_aViewData['bottom_buttons'] = 'prod';
        } else {
            $this->_aViewData['bottom_buttons'] = 'test';
        }

        $this->_aViewData['version'] = $this->_sVersion;

        return $this->_sThisTemplate;
    }

    /**
     * enable filter
     */
    public function setFilter()
    {
        $this->blfiltering = true;
    }

    /**
     * get filter
     *
     * @return bool
     */
    public function getFilter()
    {
        return $this->blfiltering;
    }

    /**
     * create a download version of the logfile
     */
    public function downloadLogFile()
    {
        $LogFile = oxNew('mo_ingenico__helper')->getLogFilePath();
        header('Pragma: no-cache');
        header('Cache-Control: no-cache');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="'. basename($LogFile) . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($LogFile));
        readfile($LogFile);
        exit;
    }


    /**
     * create a download version of the filtered logfile
     */
    public function downloadFilteredLogFile()
    {
        $this->blfiltering = true;

        $LogFile = oxNew('mo_ingenico__helper')->getLogFilePath();
        $filters = [];
        if ($this->blfiltering) {
            $aFilter = $this->getConfig()->getRequestParameter('ingenicologfilter');
            $this->_aViewData['ingenicologfilter'] = $aFilter;
            if (is_array($aFilter)) {
                $filters = array_filter($aFilter);
            }
        }

        $reader = oxNew('mo_ingenico__log_parser');
        $data = $reader->filterLogFileForDownload($LogFile, $filters);

        header('Pragma: no-cache');
        header('Cache-Control: no-cache');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="'. basename($LogFile) . '";');
        header('Content-Transfer-Encoding: binary');
        //header('Content-Length: ' . filesize($LogFile));
        array_walk($data,function ($line) {echo $line.PHP_EOL;});
        exit;
    }

}
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

        if (!$LogFile = oxNew('mo_ingenico__helper')->getLogFilePath()) {
            $this->_aViewData['logfile'] = [];
            return $this->_sThisTemplate;
        }
        $filters = $this->getFilter();
        $this->_aViewData['ingenicologfilter'] = $filters;

        $reader = oxNew('mo_ingenico__log_parser');
        $data = $reader->parseLogFile($LogFile, $filters);

        $this->_aViewData['logfile'] = $data;

        return $this->_sThisTemplate;
    }

    /**
     * enable filter
     */
    public function setIsFiltering()
    {
        $this->blfiltering = true;
    }

    /**
     * get filter
     *
     * @return bool
     */
    public function isFiltering()
    {
        return $this->blfiltering;
    }

    protected function getFilter()
    {
        if (!$this->isFiltering()) {
            return [];
        }
        return array_filter($this->getConfig()->getRequestParameter('ingenicologfilter'));
    }

    /**
     * create a download version of the logfile
     */
    public function downloadLogFile()
    {
        $logFile = oxNew('mo_ingenico__helper')->getLogFilePath();
        $this->createDownload($logFile, $logFile, true);
    }


    /**
     * create a download version of the filtered logfile
     */
    public function downloadFilteredLogFile()
    {
        $logFile = oxNew('mo_ingenico__helper')->getLogFilePath();
        $this->setIsFiltering();
        $filters = $this->getFilter();

        $reader = oxNew('mo_ingenico__log_parser');
        $data = $reader->filterLogFileForDownload($logFile, $filters);

        $content = implode(PHP_EOL, $data);
        $this->createDownload($logFile, $content, false);
    }

    /**
     * @param string  $fileName
     * @param string  $data
     * @param boolean $isFile
     */
    protected function createDownload($fileName, $data, $isFile)
    {
        header('Pragma: no-cache');
        header('Cache-Control: no-cache');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="'. basename($fileName) . '";');
        header('Content-Transfer-Encoding: binary');
        if ($isFile) {
            header('Content-Length: ' . filesize($data));
            readfile($data);
            exit;
        }
        header('Content-Length: ' . mb_strlen($data));
        oxRegistry::get('oxUtils')->showMessageAndExit($data);
    }
}
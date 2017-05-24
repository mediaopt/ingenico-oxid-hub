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
 * @package views
 * @copyright (C) Ingenico 2009, 2010
 * @version 1.3
 * $Id: mo_ingenico__template.php 6 2012-12-12 10:16:57Z martin $
 */

/**
 * @package views
 */
class mo_ingenico__template extends oxUBase
{

    protected $_sThisTemplate = 'dynamic.tpl';

    /**
     * @todo check function, maybe useless?
     */
    public function init()
    {
        $activetheme = oxRegistry::getConfig()->getConfigParam('sTheme');
        if ($activetheme !== 'azure') {
            $this->_sThisTemplate = 'dynamic_mobile.tpl';
        }

        parent::init();
    }

}

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
 * $Id: mo_ogone__setup.php 53 2014-11-28 13:54:26Z martin $
 */
// require_once 'shop_config.php';
// require_once getShopBasePath() . 'modules/mo_ogone/classes/mo_ogone__main.php';

/**
 * Modul Setup
 * Admin Menu: Shop Config -> Ogone -> Setup.
 * @package admin
 */
class mo_ogone__setup extends Shop_Config
{

    const MO_OGONE__MODULE_NAME = 'mo_ogone';

    /**
     * Current class template.
     * @var string
     */
    protected $_sThisTemplate = 'mo_ogone__setup.tpl';

    /**
     * Pre-define the ISO Payment Server Address.
     * @var string
     */
    protected $mo_ogone__is_module_installed;

    /**
     * @var array
     */
    protected $mo_ogone__shop_payment_ids = [];

    protected $mo_ogone__logger = null;

    public function init()
    {
        $this->_aViewData['mo_ogone__sqlExecutionErrors'] = '';

        $this->mo_ogone__logger = Main::getInstance()->getLogger();
        $this->mo_ogone__shop_payment_ids =
            Main::getInstance()->getService('OgonePayments')->getShopPaymentIds();

        return parent::init();
    }

    /**
     * Executes parent method parent::render() and returns name of template
     * file "ogone.tpl".
     * @todo add try catch to db call
     *
     * @return string
     * @throws \oxConnectionException
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $sCurrentAdminShop = $myConfig->getShopId();

        Main::getInstance()->getLogger()->info('Shop Id: ' . $sCurrentAdminShop);

        // define the payment methods
        $aPaymentMethods = array();
        foreach ($this->mo_ogone__shop_payment_ids as $oxidPaymentId) {
            $checked = (bool)oxDb::getDb()->getOne(
                'SELECT 1  
         FROM ' . getViewName('oxpayments') . '
         WHERE oxid = "' . $oxidPaymentId . '"');

            $langKey = $this->mo_ogone__getLanguageKeyForOxidPaymentId($oxidPaymentId);
            $aPaymentMethods[] = array('id' => $oxidPaymentId,
                'name' => $langKey,
                'checked' => $checked);
        }

        $this->_aViewData['aPaymentMethods'] = $aPaymentMethods;

        $oLang = oxRegistry::getLang();
        $iLanguage = $oLang->getBaseLanguage();
        $aLanguages = $oLang->getLanguageArray($iLanguage);

        // multi-lingual preparations
        foreach ($aLanguages as $id => $data) {
            if ($aLanguages[$iLanguage]->selected === 1) {
                switch (true) {
                    case stristr($aLanguages[$iLanguage]->abbr, 'EN'):
                        $this->_aViewData['buttons_languages'] = '1';
                        break; // English
                    case stristr($aLanguages[$iLanguage]->abbr, 'FR'):
                        $this->_aViewData['buttons_languages'] = '2';
                        break; // French
                    case stristr($aLanguages[$iLanguage]->abbr, 'NL'):
                        $this->_aViewData['buttons_languages'] = '3';
                        break; // Netherlands
                    case stristr($aLanguages[$iLanguage]->abbr, 'IT'):
                        $this->_aViewData['buttons_languages'] = '4';
                        break; // Italian
                    default:
                        $this->_aViewData['buttons_languages'] = '5';
                        break; // German
                }
            }
//            $aLanguages[$id]->aliasUsage = $myConfig->getConfigParam('ogone_sAliasUsage' . $id);
        }

        $this->_aViewData['languages'] = $aLanguages;
        $this->_aViewData['bottom_buttons'] = 'test';

        // module isn't installed
        if (!$this->mo_ogone__isModuleInstalled()) {
            $this->_aViewData['start_setup'] = true;
            // if module running in producitve mode -> linking to prod account
        } elseif ($this->getConfig()->getConfigParam('mo_ogone__isLiveMode')) {
            $this->_aViewData['bottom_buttons'] = 'prod';
        }

        $this->mo_ogone__populateViewDataWithConfigVars($sCurrentAdminShop);

        $this->_aViewData['version'] = $this->_sVersion;

        // hidden default bottom custom navi
        $this->_aViewData['sHelpURL'] = false;
        $this->_aViewData['oxid'] = $sCurrentAdminShop;

        return $this->_sThisTemplate;
    }

    protected function mo_ogone__isModuleInstalled()
    {
        if ($this->mo_ogone__is_module_installed !== null) {
            return $this->mo_ogone__is_module_installed;
        }

        $shopId = $this->getConfig()->getShopId();
        $oxDb = oxDb::getDb();

        $sql = "SELECT OXID
            FROM oxconfig 
            WHERE 
            OXVARNAME LIKE  '%ogone%'
            AND OXSHOPID = " . $oxDb->quote($shopId) . '
            LIMIT 1';

        $result = (bool)$oxDb->getOne($sql);
        $this->mo_ogone__is_module_installed = $result;
        return $result;
    }

    protected function mo_ogone__getLanguageKeyForOxidPaymentId($oxidPaymentId)
    {
        $languageKey = explode('_', $oxidPaymentId, 2);
        $languageKey = end($languageKey);
        $languageKey = 'MO_OGONE__PAYMENT_METHODS_' . strtoupper($languageKey);
        return $languageKey;
    }

    protected function mo_ogone__populateViewDataWithConfigVars($shopId)
    {
        // load configuration variables, $moduleName needs to be emtpy (only supported for custom themes, at time
        // of oxid 4.5.0, see: http://wiki.oxidforge.org/Dev_changes_in_4.5.0)
        if (!method_exists($this, '_loadConfVars')) {
            $aDbVariables = $this->loadConfVars($shopId, $moduleName = '');
        } else {
            $aDbVariables = $this->_loadConfVars($shopId, $moduleName = '');
        }

        $aConfVars = $aDbVariables['vars'];

        foreach ($this->_aConfParams as $sType => $sParam) {
            $this->_aViewData[$sParam] = $aConfVars[$sType];
        }
    }

    /**
     * Install new tables, update fields and clear cache
     */
    protected function mo_ogone__update_payments()
    {
        // get request params
        $enabledPaymentMethods = $this->mo_ogone__get_payment_methods_from_request_params();

        if (!is_array($enabledPaymentMethods)) {
            // if no checkbox is checked all payments are removed from db
            $this->mo_ogone__uninstall_payments();
        } else {
            $this->mo_ogone__install_payments();
        }
    }

    protected function mo_ogone__install_payments()
    {

        $enabledPaymentMethods = $this->mo_ogone__get_payment_methods_from_request_params();

        $aLanguageParams = array_values($this->getConfig()->getConfigParam('aLanguageParams'));

        foreach ($this->mo_ogone__shop_payment_ids as $oxidPaymentId) {

            $oResult = oxDb::getDb()->execute("SELECT * FROM oxpayments WHERE OXID = '" . $oxidPaymentId . "'");
            // add payment method to oxpayments
            if ($oResult->RecordCount() == 0 && in_array($oxidPaymentId, $enabledPaymentMethods)) {
                // add oxpayment entry
                $sQuery = "INSERT INTO `oxpayments` (`OXID`, `OXACTIVE`, `OXTOAMOUNT`, ";

                $queryLanguageColumnNames = '';
                foreach ($aLanguageParams as $aLanguageParam) {
                    if (!empty($queryLanguageColumnNames)) {
                        $queryLanguageColumnNames .= ', ';
                    }
                    if ($aLanguageParam['baseId'] > 0) {
                        $queryLanguageColumnNames .= '`OXDESC_' . $aLanguageParam['baseId'] . '` ';
                    } else {
                        $queryLanguageColumnNames .= '`OXDESC` ';
                    }
                }
                $sQuery .= $queryLanguageColumnNames;

                $sQuery .= ") VALUES ('" . $oxidPaymentId . '\', 1, 1000000, ';

                $langKey = $this->mo_ogone__getLanguageKeyForOxidPaymentId($oxidPaymentId);
                $oxLang = oxRegistry::getLang();

                $queryLanguageColumnValues = '';
                foreach ($aLanguageParams as $aLanguageParam) {
                    if (!empty($queryLanguageColumnValues)) {
                        $queryLanguageColumnValues .= ', ';
                    }
                    $queryLanguageColumnValues .=
                        oxDb::getDb()->quote($oxLang->translateString($langKey, $aLanguageParam['baseId']));
                }
                $sQuery .= $queryLanguageColumnValues;

                $sQuery .= ')';
                $this->mo_ogone__logger->info('Installing payments with query: ' . $sQuery);
                oxDb::getDb()->execute($sQuery);

            } // delete payment method from oxpayments
            elseif ($oResult->RecordCount() !== 0 && !in_array($oxidPaymentId, $enabledPaymentMethods, false)) {
                $this->mo_ogone__logger->info('Deleting payment: ' . $oxidPaymentId);

                // delete oxpayments entry
                $payment = oxNew('oxpayment');
                $payment->load($oxidPaymentId);
                $payment->delete();
            }
        }
    }

    protected function mo_ogone__get_payment_methods_from_request_params()
    {
        $methods = oxRegistry::getConfig()->getRequestParameter('ogone_aPaymentMethods');
        if (is_array($methods)) {
            array_unique($methods);
        }
        return $methods;
    }

    // TODO: HK: delete only payments from current shop -> check shop id and related tables
    protected function mo_ogone__uninstall_payments()
    {
        $deleteQueries = array(
            // delete all oxpayments entries wich starts with ogone
            "DELETE FROM oxpayments WHERE OXID LIKE 'ogone_%'",
            // delete all oxobject2group entries wich starts with ogone
            "DELETE FROM oxobject2group WHERE OXOBJECTID LIKE 'ogone%'",
            // delete all oxobject2payment oxdelset-entries wich starts with ogone
            "DELETE FROM oxobject2payment WHERE OXPAYMENTID LIKE 'ogone%' AND OXTYPE = 'oxdelset'",
            // delete all oxobject2payment oxcountry-entries wich starts with ogone
            "DELETE FROM oxobject2payment WHERE OXPAYMENTID LIKE 'ogone%' AND OXTYPE = 'oxcountry'"
        );

        foreach ($deleteQueries as $sql) {
            $this->mo_ogone__logger->info('Uninstalling payments with query: ' . $sql);
            oxDb::getDb()->execute($sql);
        }
    }

    /**
     * Saves shop configuration variables
     *
     * @return null
     */
    public function saveConfVars()
    {
        $myConfig = $this->getConfig();

        $aConfBools = oxRegistry::getConfig()->getRequestParameter('confbools');
        $aConfStrs = oxRegistry::getConfig()->getRequestParameter('confstrs');
        $aConfArrs = oxRegistry::getConfig()->getRequestParameter('confarrs');
        $aConfAarrs = oxRegistry::getConfig()->getRequestParameter('confaarrs');

        // special case for min order price value
        if ($aConfStrs['iMinOrderPrice']) {
            $aConfStrs['iMinOrderPrice'] = str_replace(',', '.', $aConfStrs['iMinOrderPrice']);
        }

        if (is_array($aConfBools)) {
            foreach ($aConfBools as $sVarName => $sVarVal) {
                $myConfig->saveShopConfVar('bool', $sVarName, $sVarVal);
            }
        }

        if (is_array($aConfStrs)) {
            foreach ($aConfStrs as $sVarName => $sVarVal) {
                $myConfig->saveShopConfVar('str', $sVarName, $sVarVal);
            }
        }

        if (is_array($aConfArrs)) {
            foreach ($aConfArrs as $sVarName => $aVarVal) {
                // home country multiple selectlist feature
                if (!is_array($aVarVal)) {
                    $aVarVal = $this->_multilineToArray($aVarVal);
                }
                $myConfig->saveShopConfVar('arr', $sVarName, $aVarVal);
            }
        }

        if (is_array($aConfAarrs)) {
            foreach ($aConfAarrs as $sVarName => $aVarVal) {
                $myConfig->saveShopConfVar('aarr', $sVarName, $this->_multilineToAarray($aVarVal));
            }
        }
    }

    protected function mo_ogone__update_payment_options()
    {
        $oxConfig = $this->getConfig();
        $paymentOptionParameters =
            $oxConfig->getRequestParameter('mo_ogone__paymentOptions') ?
                $oxConfig->getRequestParameter('mo_ogone__paymentOptions') :
                array();
        $oxConfig->saveShopConfVar('arr', 'mo_ogone__paymentOptions', $paymentOptionParameters);
    }

    /**
     * Saves main user parameters.
     *
     * @return mixed
     */
    public function save()
    {
        $this->mo_ogone__update_payments();
        $this->mo_ogone__update_payment_options();
        $this->saveConfVars();
    }

    public function mo_ogone__isBrandActive($pm, $brand)
    {
        return oxNew('mo_ogone__helper')->mo_ogone__isBrandActive($pm, $brand);
    }

    public function mo_ogone__getBrands($oxidPaymentId)
    {
        return Main::getInstance()->getService('OgonePayments')->getPaymentMethodProperty($oxidPaymentId, 'brand');
    }

}

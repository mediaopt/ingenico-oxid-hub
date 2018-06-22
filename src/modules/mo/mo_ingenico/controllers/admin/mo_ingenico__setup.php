<?php

use Mediaopt\Ingenico\Sdk\Main;

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
 * $Id: mo_ingenico__setup.php 53 2014-11-28 13:54:26Z martin $
 */
// require_once 'shop_config.php';
// require_once getShopBasePath() . 'modules/mo_ingenico/classes/mo_ingenico__main.php';

/**
 * Modul Setup
 * Admin Menu: Shop Config -> Ingenico -> Setup.
 * @package admin
 */
class mo_ingenico__setup extends Shop_Config
{

    const MO_INGENICO__MODULE_NAME = 'mo_ingenico';

    /**
     * Current class template.
     * @var string
     */
    protected $_sThisTemplate = 'mo_ingenico__setup.tpl';

    /**
     * Pre-define the ISO Payment Server Address.
     * @var string
     */
    protected $mo_ingenico__is_module_installed;

    /**
     * @var array
     */
    protected $mo_ingenico__shop_payment_ids = [];

    protected $mo_ingenico__logger = null;

    public function init()
    {
        $this->mo_ingenico__logger = Main::getInstance()->getLogger();
        $this->mo_ingenico__shop_payment_ids = Main::getInstance()->getService('IngenicoPayments')->getShopPaymentIds();
        return parent::init();
    }

    /**
     * set list of installed payments
     *
     * @return string
     * @throws \oxConnectionException
     */
    public function render()
    {
        parent::render();
        // define the payment methods
        $installedPayments = $this->mo_ingenico__get_payment_methods_from_db();
        $this->_aViewData['aPaymentMethods'] = array_map(function ($oxidPaymentId) use ($installedPayments) {
            return [
                'id'      => $oxidPaymentId,
                'name'    => $this->mo_ingenico__getLanguageKeyForOxidPaymentId($oxidPaymentId),
                'checked' => in_array($oxidPaymentId, $installedPayments, true)
            ];
        }, $this->mo_ingenico__shop_payment_ids);
        return $this->_sThisTemplate;
    }

    /**
     * get list of installed payments
     *
     * @return string[]
     */
    protected function mo_ingenico__get_payment_methods_from_db()
    {
        $storedPaymentsQuery = 'SELECT OXID FROM oxpayments WHERE OXID IN ("' . implode('", "', $this->mo_ingenico__shop_payment_ids) . '")';
        $storedPayments = \oxdb::getDb()->getCol($storedPaymentsQuery);

        return $storedPayments;
    }

    /**
     * check if the payment module is installed
     *
     * @return bool
     */
    protected function mo_ingenico__isModuleInstalled()
    {
        if ($this->mo_ingenico__is_module_installed !== null) {
            return $this->mo_ingenico__is_module_installed;
        }

        $shopId = $this->getConfig()->getShopId();
        $oxDb = oxDb::getDb();

        $result = (bool) $oxDb->getOne('SELECT 1 FROM oxconfig WHERE OXVARNAME LIKE  "%ingenico%" AND OXSHOPID = ? LIMIT 1', [$shopId]);
        $this->mo_ingenico__is_module_installed = $result;
        return $result;
    }

    /**
     * get language key to translate the payment method (e.g. 'MO_INGENICO__PAYMENT_METHODS_CREDIT_CARD')
     *
     * @param string $oxidPaymentId
     *
     * @return string
     */
    protected function mo_ingenico__getLanguageKeyForOxidPaymentId($oxidPaymentId)
    {
        $languageKey = explode('_', $oxidPaymentId, 2);
        $languageKey = end($languageKey);
        $languageKey = 'MO_INGENICO__PAYMENT_METHODS_' . strtoupper($languageKey);
        return $languageKey;
    }

    /**
     * activate new payments and delete old ones
     */
    protected function mo_ingenico__update_payments()
    {
        $db = \oxdb::getDb();
        $enabledPaymentMethods = $this->mo_ingenico__get_payment_methods_from_request_params();
        $storedPayments = $this->mo_ingenico__get_payment_methods_from_db();

        $aLanguageParams = array_values($this->getConfig()->getConfigParam('aLanguageParams'));
        foreach (array_diff($enabledPaymentMethods, $storedPayments) as $oxidPaymentId) {
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

            $langKey = $this->mo_ingenico__getLanguageKeyForOxidPaymentId($oxidPaymentId);
            $oxLang = oxRegistry::getLang();

            $queryLanguageColumnValues = '';
            foreach ($aLanguageParams as $aLanguageParam) {
                if (!empty($queryLanguageColumnValues)) {
                    $queryLanguageColumnValues .= ', ';
                }
                $queryLanguageColumnValues .=
                    $db->quote($oxLang->translateString($langKey, $aLanguageParam['baseId']));
            }
            $sQuery .= $queryLanguageColumnValues;

            $sQuery .= ')';
            $this->mo_ingenico__logger->info(sprintf('Installing payment %s with query: %s', $oxidPaymentId, $sQuery));
            $db->execute($sQuery);
        }
        foreach (array_diff($storedPayments, $enabledPaymentMethods) as $oxidPaymentId) {
            // delete oxpayment entry
            $this->mo_ingenico__logger->info('Deleting payment: ' . $oxidPaymentId);

            $payment = oxNew('oxpayment');
            $payment->load($oxidPaymentId);
            $payment->delete();
        }
    }

    /**
     * get list of payments that where chosen by the admin user
     *
     * @return array
     */
    protected function mo_ingenico__get_payment_methods_from_request_params()
    {
        $methods = oxRegistry::getConfig()->getRequestParameter('ingenico_aPaymentMethods');
        return is_array($methods) ? array_unique($methods) : [];
    }

    /**
     * write changes in payment options into config
     */
    protected function mo_ingenico__update_payment_options()
    {
        $oxConfig = $this->getConfig();
        $paymentOptionParameters = $oxConfig->getRequestParameter('mo_ingenico__paymentOptions') ?: [];
        $oxConfig->saveShopConfVar('arr', 'mo_ingenico__paymentOptions', $paymentOptionParameters);
    }

    /**
     * Saves main user parameters.
     */
    public function save()
    {
        $this->mo_ingenico__update_payments();
        $this->mo_ingenico__update_payment_options();
    }

    /**
     * check if given brand for given payment id is active
     *
     * @param string $pm
     * @param string $brand
     *
     * @return bool
     */
    public function mo_ingenico__isBrandActive($pm, $brand)
    {
        return oxNew('mo_ingenico__helper')->mo_ingenico__isBrandActive($pm, $brand);
    }

    /**
     * get brand or list of brands for a given payment id
     *
     * @param $oxidPaymentId
     *
     * @return string|string[]
     */
    public function mo_ingenico__getBrands($oxidPaymentId)
    {
        return Main::getInstance()->getService('IngenicoPayments')->getPaymentMethodProperty($oxidPaymentId, 'brand');
    }

}

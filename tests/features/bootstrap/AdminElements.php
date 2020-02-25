<?php
/**
 * @author  Mediaopt GmbH
 * @author  ibrahim.hilali@mediaopt.de
 * @version 1.0
 */

interface AdminElements
{
    /**
     * @var string[]
     */
    const ADMIN_TABS = [
        'payment'  => self::ADMIN_PAYMENT_TAB,
        'main'     => self::ADMIN_MAIN_TAB,
        'users'    => self::ADMIN_USERS_TAB,
        'settings' => self::ADMIN_API_SETTINGS_TAB,
    ];

    /**
     * @var mixed[][]
     */
    const  AMDMIN_API_SETTINGS_VALUES = [
        'live mode'               => ['type' => 'checkbox', 'selector' => 'input[name="confbools[mo_ingenico__isLiveMode]"][type="hidden"]+input[name="confbools[mo_ingenico__isLiveMode]"]', 'value' => false],
        'credit card transaction' => ['type' => 'checkbox', 'selector' => 'input[name="confbools[mo_ingenico__capture_creditcard]"]', 'value' => true],
        'utf-8 encode'            => ['type' => 'checkbox', 'selector' => 'input[name="confbools[mo_ingenico__use_utf8]"]:nth-child(2)', 'value' => true],
        'set payment date'        => ['type' => 'checkbox', 'selector' => 'input[name="confbools[mo_ingenico__set_oxpaid]"]:nth-child(2)', 'value' => true],
        'hidden authorization'    => ['type' => 'checkbox', 'selector' => 'input[name="confbools[mo_ingenico__use_hidden_auth]"]:nth-child(2)', 'value' => true],
        'iframe'                  => ['type' => 'checkbox', 'selector' => 'input[name="confbools[mo_ingenico__use_iframe]"]:nth-child(2)', 'value' => true],
        'use alias manager'       => ['type' => 'checkbox', 'selector' => 'input[name="confbools[mo_ingenico__use_alias_manager]"]:nth-child(2)', 'value' => true],
        'oxtransid'               => ['type' => 'select', 'selector' => 'select[name="confselects[mo_ingenico__transid_param]"]', 'value' => 'ORDERID'],
        'pspid'                   => ['type' => 'input', 'selector' => 'input[name="confstrs[ingenico_sPSPID]"]', 'value' => 'MEDIAOPT41'],
        'user id'                 => ['type' => 'input', 'selector' => 'input[name="confstrs[mo_ingenico__api_userid]"]', 'value' => 'MEDIAOPT41_API'],
        'user password'           => ['type' => 'input', 'selector' => 'input[name="confstrs[mo_ingenico__api_userpass]"]', 'value' => 'apiUser_41'],
        'hash algorithm'          => ['type' => 'select', 'selector' => 'select[name="confselects[ingenico_sHashingAlgorithm]"]', 'value' => 'SHA-512'],
        'sha in signature'        => ['type' => 'input', 'selector' => 'input[name="confstrs[ingenico_sSecureKeyIn]"]', 'value' => 'hallo1234Mediaopt'],
        'sha out signature'       => ['type' => 'input', 'selector' => 'input[name="confstrs[ingenico_sSecureKeyOut]"]', 'value' => 'hallo1234Mediaopt'],
        'time out'                => ['type' => 'input', 'selector' => 'input[name="confstrs[mo_ingenico__timeout]"]', 'value' => '15'],

    ];

    /**
     * @var string
     */
    const ADMIN_API_SETTINGS_SAVE_BUTTON = 'input[name="save"]';

    /**
     * @var string
     */
    const ADMIN_API_SETTINGS_TAB = 'a[href="#module_config"]';


    /**
     * @var string
     */
    const ADMIN_ADI_SETTINGS_COLLAPSE_MENU = '#moduleConfiguration > div:nth-child(7) a';

    /**
     * @var string
     */
    const FRAME_MAIN_NAVIGATION = 'navigation';

    /**
     * @var string
     */
    const FRAME_LEFT_NAVIGATION = 'adminnav';

    /**
     * @var string
     */
    const FRAME_LIST = 'list';

    /**
     * @var string
     */
    const FRAME_EDIT = 'edit';

    /**
     * @var string
     */
    const FRAME_BASIC = 'basefrm';

    /**
     * @var string
     */
    const ADMIN_PAYMENT_TAB = 'a[href="#deliveryset_payment"]';

    /**
     * @var string
     */
    const ADMIN_MAIN_TAB = 'a[href="#deliveryset_main"]';

    /**
     * @var string
     */
    const ADMIN_USERS_TAB = 'a[href="#deliveryset_users"]';

    /**
     * @var string
     */
    const ADMIN_LOGIN_USER_INPUT = 'input#usr';

    /**
     * @var string
     */
    const ADMIN_LOGIN_PASSWORD_INPUT = 'input#pwd';

    /**
     * @var string
     */
    const ADMIN_LOGIN_SUBMIT_FORM = '#login';

    /**
     * @var string
     */
    const ADMIN_LOGIN_SUBMIT_BUTTON = 'input[type=submit]';

    /**
     * @var string
     */
    const ADMIN_EXTENSION_NAVIGATION = '#nav-1-3';

    /**
     * @var string
     */
    const ADMIN_MODELS_SETTINGS_NAVIGATION = '#nav-1-3-2';

    /**
     * @var string
     */
    const ADMIN_MODULES_ACTIVATION_LIST = '#liste form input[value=module_list]';

    /**
     * @var string
     */
    const ADMIN_MODULES_LIST_ITEMS = 'div#liste tr td.active + td a';

    /**
     * @var string
     */
    const ADMIN_INGENICO_MODULE_LIST_ITEMS = 'td + td a[href="Javascript:top.oxid.admin.editThis(\'mo_ingenico\');"]';

    /**
     * @var string
     */
    const ADMIN_INGENICO_MODULE_ACTIVATION_BUTTON = '#module_activate';

    /**
     * @var string
     */
    const  ADMIN_INGENICO_MODULE_STATUS_ACTIVE = 'td.active + td a[href="Javascript:top.oxid.admin.editThis(\'mo_ingenico\');"]';

    /**
     * @var string
     */
    const ADMIN_INGENICO_NAVIGATION_MENU = 'li#nav-1-10';

    /**
     * @var string
     */
    const ADMIN_INGENICO_NAVIGATION_MENU_SETTINGS = 'li#nav-1-10-1';

    /**
     * @var string
     */
    const ADMIN_INGENICO_NAVIGATION_MENU_SETTINGS_UPDATE_BUTTON = 'input[type="submit"]';

    /**
     * @var string
     */
    const ADMIN_NAVIGATION_MENU_SHOP_SETTINGS = '#nav-1-2';
    /**
     * @var string
     */
    const ADMIN_NAVIGATION_MENU_SHOP_SETTINGS_SHIPPING_METHOD = '#nav-1-2-3';

    /**
     * @var string
     */
    const ADMIN_STANDARD_PAYMENT_LIST_ITEM = 'tr[id ="row.1"] > td > div > a';

    /**
     * @var string
     */
    const  ADMIN_STANDARD_PAYMENT_ACTIVE_STATUS = 'td[class*="tab active"] a[href="#deliveryset_main"]';

    /**
     * @var string
     */
    const ADMIN_STANDARD_PAYMENT_TAB_ASSIGN_PAYMENT_BUTTON = 'input[value="Assign Payment Methods"]';

    /**
     * @var string
     */
    const ADMIN_ASSIGN_DIALOG_BOX_ASSIGN_BUTTON = 'button[id*=container1_btn]';

    /**
     * @var string
     */
    const ADMIN_ASSIGN_DIALOG_LEFT_ITEMS_EXISTENCE = 'div[id*="container1"] .yui-dt-data td:first-child div div';

    /**
     * @var string
     */
    const ADMIN_NAVIGATION_MENU_SHOP_SETTINGS_SELECTED = '#nav-1-2.exp';

    /**
     * @var string
     */
    const ADMIN_NAVIGATION_MENU_PAYMENT_METHOD_MENU = '#nav-1-2-1';

    /**
     * @var string
     */
    const ADMIN_STANDARD_PAYMENT_TAB_ASSIGN_USER_GROUP_BUTTON = '#myedit input[value="Assign User Groups"]';

    /**
     * @var string
     */
    const ADMIN_STANDARD_PAYMENT_TAB_ASSIGN_COUNTRIES_BUTTON = '#myedit input[value="Assign Countries"]';
}
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
 * @package out
 * @copyright (C) Ingenico 2009, 2010, 2011
 * @version 1.3
 * $Id: mo_ingenico__lang.php 25 2013-05-24 15:26:26Z martin $
 */
$sLangName = 'English';

// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
    'charset'                                      => 'ISO-8859-15',

    //API config
    'SHOP_MODULE_GROUP_moIngenicoApiConfiguration' => 'API configuration',
    'SHOP_MODULE_mo_ingenico__isLiveMode' => 'live mode',
    'HELP_SHOP_MODULE_mo_ingenico__isLiveMode' => 'Deactivate this option for testing. '
        . 'For production mode, aktivate this option.',
    'SHOP_MODULE_mo_ingenico__logLevel' => 'Log level',
    'HELP_SHOP_MODULE_mo_ingenico__logLevel' => 'Configure when log entries should be written to log file',
    'SHOP_MODULE_mo_ingenico__logLevel_DEBUG' => 'Debug',
    'SHOP_MODULE_mo_ingenico__logLevel_INFO' => 'Info',
    'SHOP_MODULE_mo_ingenico__logLevel_ERROR' => 'Error',
    'SHOP_MODULE_mo_ingenico__capture_creditcard' => 'credit card transaction reservations',
    'HELP_SHOP_MODULE_mo_ingenico__capture_creditcard' => 'If disabled credit card transactions will be captured immediately',
    'SHOP_MODULE_mo_ingenico__use_utf8' => 'UTF-8 encode',
    'HELP_SHOP_MODULE_mo_ingenico__use_utf8' => 'Submit data UTF8-encoded.Ingenico Backend and shop configuration have to be the same. Configuration -> Technical information -> Global security parameters',
    'SHOP_MODULE_mo_ingenico__set_oxpaid' => 'Set payment date',
    'HELP_SHOP_MODULE_mo_ingenico__set_oxpaid' => 'Sets the payment date, when the PSP returns an okay-state.<br />' .
        'ATTENTION: This state does not mean that the money was transfered to the shop account.',
    'SHOP_MODULE_mo_ingenico__use_hidden_auth' => 'Hidden Authorization',
    'HELP_SHOP_MODULE_mo_ingenico__use_hidden_auth' => 'When using Hidden Authorization the user will not be redirected to the PSP to enter his payment information.',
    'SHOP_MODULE_mo_ingenico__use_iframe' => 'via iFrame',
    'HELP_SHOP_MODULE_mo_ingenico__use_iframe' => '' .
        'Has no effect when hidden authorization is disabled<br />' .
        'true: the customer puts his card information in an iFrame of the psp (PCI konform)</br>' .
        'false: the customer puts his card information into a form on the shopsite that is sent to the psp (not PCI conform, PCI-Certificat necessary)',
    'SHOP_MODULE_mo_ingenico__use_alias_manager' => 'Use Alias Manager',
    'HELP_SHOP_MODULE_mo_ingenico__use_alias_manager' => '' .
        'Stores customers credit card alias data for future payments',
    'SHOP_MODULE_mo_ingenico__transid_param' => 'Which ID to store as OXTRANSID in OXORDER',
    'HELP_SHOP_MODULE_mo_ingenico__transid_param' => '' .
        'Specifies which value should be stored as OXTRANSID in the table OXORDER:<br />' .
        'ORDERID: the ID generated by the module (e.g.: mo_ingenico_123456)<br />' .
        'PAYID: the ID generated by Ingenico',
    'SHOP_MODULE_mo_ingenico__transid_param_ORDERID' => 'ORDERID',
    'SHOP_MODULE_mo_ingenico__transid_param_PAYID' => 'PAYID',
    'SHOP_MODULE_ingenico_sPSPID' => 'your PSPID',
    'HELP_SHOP_MODULE_ingenico_sPSPID' => 'Enter your the (Test)-PSPID , you received from Payment Service Provider.',
    'SHOP_MODULE_mo_ingenico__api_userid' => 'API-user id',
    'HELP_SHOP_MODULE_mo_ingenico__api_userid' => 'You can mangage API-users in Ingenico Backend.  Configuration -> Users',
    'SHOP_MODULE_mo_ingenico__api_userpass' => 'API-user password',
    'HELP_SHOP_MODULE_mo_ingenico__api_userpass' => 'You can mangage passwords for API-users in ingenico backend. Configuration -> Users',
    'SHOP_MODULE_ingenico_sHashingAlgorithm' => 'Hash algorithm',
    'HELP_SHOP_MODULE_ingenico_sHashingAlgorithm' => 'Which algorithm will be used for SHA value calculation. Ingenico Backend and shop configuration have to be the same. Configuration -> Technical information -> Global security parameters',
    'SHOP_MODULE_ingenico_sHashingAlgorithm_SHA-1' => 'SHA-1',
    'SHOP_MODULE_ingenico_sHashingAlgorithm_SHA-256' => 'SHA-256',
    'SHOP_MODULE_ingenico_sHashingAlgorithm_SHA-512' => 'SHA-512',
    'SHOP_MODULE_ingenico_sSecureKeyIn' => 'SHA-In signature',
    'HELP_SHOP_MODULE_ingenico_sSecureKeyIn' => 'Key for SHA Value calculation for outgoing calls. Ingenico Backend and shop configuration have to be the same. Configuration -> Technical information -> Data and origin verification',
    'SHOP_MODULE_ingenico_sSecureKeyOut' => 'SHA-Out signature',
    'HELP_SHOP_MODULE_ingenico_sSecureKeyOut' => 'Key for SHA Value calculation for incoming calls. Ingenico Backend and shop configuration have to be the same. Configuration -> Technical information -> Transaction feedback',
    'SHOP_MODULE_mo_ingenico__timeout' => 'Timout for API calls',
    'HELP_SHOP_MODULE_mo_ingenico__timeout' => 'Timeout for transactions in seconds.',
    'SHOP_MODULE_mo_ingenico__debug_mode' => 'Debug Modus',
    'HELP_SHOP_MODULE_mo_ingenico__debug_mode' => 'If activated, additional information are visible. Deactivate for produktive mode!',

    //payment page layout config
    'SHOP_MODULE_GROUP_moIngenicoLayoutPaymentPage' => 'Payment page layout',
    'SHOP_MODULE_ingenico_sTemplate' => 'Use dynamic template',
    'HELP_SHOP_MODULE_ingenico_sTemplate' => 'If deactivated, the static template is used.',
    'SHOP_MODULE_ingenico_sTplPMListStyle' => 'Payment methods layout',
    'HELP_SHOP_MODULE_ingenico_sTplPMListStyle' => 'Arrange the list of payment methods on the payment page',
    'SHOP_MODULE_ingenico_sTplPMListStyle_0' => 'Group logos horizontally with the group names on the left',
    'SHOP_MODULE_ingenico_sTplPMListStyle_1' => 'Group logos horizontally without group names',
    'SHOP_MODULE_ingenico_sTplPMListStyle_2' => 'Group logos vertically with name',
    'SHOP_MODULE_ingenico_blBackButton' => 'Display back button',
    'HELP_SHOP_MODULE_ingenico_blBackButton' => 'Should a "back" button be visible?',
    'SHOP_MODULE_ingenico_blTplTitle' => 'enable title and header of the payment page',
    'HELP_SHOP_MODULE_ingenico_blTplTitle' => 'If active, the content of CMS snippet "mo_ingenico_tplTitle" will be used.',
    'SHOP_MODULE_ingenico_sTplBGColor' => 'Background color',
    'HELP_SHOP_MODULE_ingenico_sTplBGColor' => 'Predefined value: white',
    'SHOP_MODULE_ingenico_sTplFontColor' => 'Text color',
    'HELP_SHOP_MODULE_ingenico_sTplFontColor' => 'Predefined value: black',
    'SHOP_MODULE_ingenico_sTplTableBGColor' => 'Table background color',
    'HELP_SHOP_MODULE_ingenico_sTplTableBGColor' => 'Predefined value: white',
    'SHOP_MODULE_ingenico_sTplTbFontColor' => 'Table text color',
    'HELP_SHOP_MODULE_ingenico_sTplTbFontColor' => 'Predefined value: black',
    'SHOP_MODULE_ingenico_sTplBtnBGColor' => 'Button background color',
    'HELP_SHOP_MODULE_ingenico_sTplBtnBGColor' => 'Predefined value: nothing',
    'SHOP_MODULE_ingenico_sTplBtnFontColor' => 'Button text color',
    'HELP_SHOP_MODULE_ingenico_sTplBtnFontColor' => 'Predefined value: black',
    'SHOP_MODULE_ingenico_sTplFontFamily' => 'Font',
    'HELP_SHOP_MODULE_ingenico_sTplFontFamily' => 'Predefined value: Verdana',
    'SHOP_MODULE_ingenico_sTplLogo' => 'Your logo',
    'HELP_SHOP_MODULE_ingenico_sTplLogo' => 'URL/filename of the logo you want to display at the top of the payment page next to the title. The URL must be absolute (contain the full path), it cannot be relative. '
    . 'The logo needs to be stored on a secure server (https://www.yourshop.com). If you do not have a secure environment to '
    . 'store your image, you can send a JPG, PNG or GIF file (and your PSPID) to support@ingenico.com (only for production'
    . 'accounts since this is a paying option! Please activate the Logo Hosting option in your Account > Options page before sending us'
    . 'your logo). If the logo is stored on our servers, you only need to enter the filename, not the whole URL.',

    //interface
    'SHOP_MODULE_GROUP_moIngenicoInterface' => 'Status overview',
    'SHOP_MODULE_mo_ingenico__useGroupBy' => 'Display identical entries only once',
    'HELP_SHOP_MODULE_mo_ingenico__useGroupBy' => 'If deferred feedback is activated, it may occur that for a' .
        ' transaction 2 identical log entries are saved. There are coming from different sources '
        . '(customer and deferred feedback). To improve the usability,'
        . ' identical entries are only displayed once.',

//aftersales
    'MO_INGENICO__REFUND_INCLUDE_SHIPMENT'  => 'Refund shipping costs',
    'MO_INGENICO__REFUND_INCLUDE_GIFTCARD'  => 'Refund giftcard costs',
    'MO_INGENICO__CAPTURE_INCLUDE_SHIPMENT' => 'Capture shipping costs',
    'MO_INGENICO__CAPTURE_INCLUDE_GIFTCARD' => 'Capture giftcard costs',
    'mo_ingenico__aftersales_capture' => 'Ingenico Payment capture',
    'mo_ingenico__aftersales_refund'  => 'Ingenico Payment refund',
    'MO_INGENICO__REFUND_EXPLANATION' => 'Here you have the possibility to refund captured payments.' .
        ' Please keep in mind, that payments can only be refunded if they were captured and not refunded yet.' .
        ' The overall amount that is refunded can not exceed the total amount of the order.',
    'MO_INGENICO__CAPTURE_EXPLANATION' => 'Here you have the possibility to capture reserved payments.' .
        ' Please keep in mind, that payments can only be captured if they were reserved and not captured yet.' .
        ' The overall amount that is captured can not exceed the total amount of the order.',

// Admin Menu
    'mo_ingenico'                                     => 'Ingenico',
    'mo_ingenico__setup'                              => 'Settings',
    'mo_ingenico__interface'                          => 'Status overview',
    'mo_ingenico__logfile'                            => 'Log',
// Admin -> Ingenico -> Statusmeldungen
    'INGENICO_DATE'                                   => 'Date',
    'INGENICO_ORDERID'                                => 'Order ID',
    'INGENICO_TRANSID'                                => 'Trans.ID',
    'INGENICO_CUSTOMER_NAME'                          => 'Customer',
    'INGENICO_CN'                                     => 'Card holder',
    'INGENICO_AMOUNT'                                 => 'Amount',
    'INGENICO_ACCEPTANCE'                             => 'Authorization',
    'INGENICO_STATUS'                                 => 'Status',
    'INGENICO_CARDNO'                                 => 'Card number',
    'INGENICO_BRAND'                                  => 'Brand',
    'INGENICO_ED'                                     => 'Expiry date',
    'INGENICO_PAYID'                                  => 'Pay ID',
    'INGENICO_NCERROR'                                => 'Error',
    'INGENICO_TRXDATE'                                => 'Transaction date',
// Admin -> Ingenico -> Log
    'MO_INGENICO_MESSAGE' => 'Message',
    'MO_INGENICO_CONTEXT' => 'Context',
    'MO_INGENICO_EXTRA' => 'Extra Information',
// Admin -> Ingenico -> Einstellungen
    'MO_INGENICO__ISLIVEMODE'                         => 'Live-Mode',
    'MO_INGENICO_NOTHINGINTHELOGS'                    => 'Currently there is nothing in the logs',
    'MO_INGENICO_LOGLEVEL'                            => 'Log-Level',
    'MO_INGENICO_LOGLEVEL_DEBUG'                      => 'Debug',
    'MO_INGENICO_LOGLEVEL_INFO'                       => 'Info',
    'MO_INGENICO_LOGLEVEL_ERROR'                      => 'Error',
    'HELP_INGENICO_PSPID'                             => 'Please enter your (Test)-PSPID you have received from your Payment Service Provider.',
    'START_SETUP'                                  => 'Start installation',
    'UPDATE_SETUP'                                 => 'Update',
    'INGENICO_PAYMENT_METHODS'                        => 'Please activate the payment methods<br />you use with your Payment Service Provider:',
    'INGENICO_PAYMENT_METHODS_ALL_PAYMENT_METHODS'    => 'All payment methods',
// Zahlarten
    'MO_INGENICO__PAYMENT_METHODS_CREDIT_CARD'        => 'Credit card (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_MAESTRO'            => 'Maestro (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_POST_CARD'          => 'Post Card (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_GIROPAY'            => 'giropay (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNG' => 'DirectEbanking only (for German accounts only) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNGAT' => 'DirectEbanking (AT) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNGBE' => 'DirectEbanking (BE) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNGCH' => 'DirectEbanking (CH) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNGDE' => 'DirectEbanking (DE) For NON-German accounts who wish to accept this PM for the German market ',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNGFR' => 'DirectEbanking (FR) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNGGB' => 'DirectEbanking (GB) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNGIT' => 'DirectEbanking (IT) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNGNL' => 'DirectEbanking (NL) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_IDEAL'              => 'iDEAL (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_POST_EFINANCE'      => 'Post e-Finance (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_EPS'                => 'eps Austria (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_DEBIT_DE'           => 'Direct Debits DE (ELV) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_DEBIT_NL'           => 'Direct Debits NL (Machtigingen) (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_PAYPAL'             => 'PayPal (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_MPASS'              => 'mPass (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_OPEN_INVOICE_DE'    => 'BillPay (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_MISTER_CASH'        => 'Mister Cash (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_VVV'                => 'VVV Gift Card (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_DANKORT'            => 'Dankort (Ingenico)',
    'INGENICO_PAYMENT_METHODS_AMEX'                   => 'American Express',
    'INGENICO_PAYMENT_METHODS_VISA'                   => 'VISA',
    'INGENICO_PAYMENT_METHODS_MASTERCARD'             => 'MasterCard',
    'INGENICO_PAYMENT_METHODS_JCB'                    => 'JCB',
    'INGENICO_PAYMENT_METHODS_BCMC'                   => 'Bancontact/Mister Cash',
    'INGENICO_PSPID'                                  => 'Your PSPID:',
    'INGENICO_USERID'                                 => 'Your USERID:',
    'INGENICO_URL'                                    => 'Payment Gateway URL:',
    'PAYMENT_MAIN_REQUEST'                         => 'Payment Gateway call:',
    'PAYMENT_MAIN_REQUEST_IFRAME'                  => 'iFrame',
    'PAYMENT_MAIN_REQUEST_REDIRECT'                => 'Redirection',
    'HELP_PAYMENT_MAIN_REQUEST'                    => 'Payment page call',
    'INGENICO_SECURE_KEY_IN'                          => 'SHA-IN Signature:',
    'INGENICO_SECURE_KEY_OUT'                         => 'SHA-OUT Signature:',
    'INGENICO_HASHING'                                => 'SHA Hashing:',
    'INGENICO_HASHING_ALGORITHM'                      => 'Hash Algorithm:',
    'INGENICO_HASHING_ALGORITHM_SHA_1'                => 'SHA-1',
    'INGENICO_HASHING_ALGORITHM_SHA_256'              => 'SHA-256',
    'INGENICO_HASHING_ALGORITHM_SHA_512'              => 'SHA-512',
    'INGENICO_HASHING_ENCODING_1'                     => 'AUTO',
    'INGENICO_HASHING_ENCODING_2'                     => 'ISO-8859-1',
    'INGENICO_HASHING_ENCODING_3'                     => 'UTF-8',
    'INGENICO_OPERATION'                              => 'Operation:',
    'INGENICO_OPERATION_RES'                          => 'Authorization',
    'INGENICO_OPERATION_SAL'                          => 'Direct sale',
    'INGENICO_MODULE_DESCRIPTION'                     => 'For further information on the extension, please see the documentation and FAQ which you can find in the Support section of your Ingenico account.',
    'INGENICO_TEST_ACCOUNT'                           => 'Test account',
    'INGENICO_PROD_ACCOUNT'                           => 'Production account',
    'INGENICO_TEMPLATE'                               => 'Payment page layout:',
    'INGENICO_TEMPLATE_TRUE'                          => 'Dynamic template',
    'INGENICO_TEMPLATE_FALSE'                         => 'Static template',
    'INGENICO_PMLISTSTYLE_TITLE'                      => 'Payment method layout:',
    'INGENICO_PMLISTSTYLE_TITLE_DESCRIPTION'          => 'Arrange the list of payment methods on the payment page',
    'INGENICO_PMLISTSTYLE_0'                          => 'Group logos horizontally with the group names on the left',
    'INGENICO_PMLISTSTYLE_1'                          => 'Group logos horizontally without group names',
    'INGENICO_PMLISTSTYLE_2'                          => 'Group logos vertically with name',
    'INGENICO_BACK_TITLE'                             => 'Back button:',
    'INGENICO_TEMPLATE_TITLE'                         => 'Title and Header of the payment page:',
    'INGENICO_TEMPLATE_TITLE_DESCRIPTION'             => 'Pre-configured value:',
    'INGENICO_TEMPLATE_BGCOLOR'                       => 'Background color:',
    'INGENICO_TEMPLATE_BGCOLOR_DESCRIPTION'           => 'Pre-configured value: white',
    'INGENICO_TEMPLATE_FONTCOLOR'                     => 'Text color:',
    'INGENICO_TEMPLATE_FONTCOLOR_DESCRIPTION'         => 'Pre-configured value: black',
    'INGENICO_TEMPLATE_TABLE_BGCOLOR'                 => 'Table background color:',
    'INGENICO_TEMPLATE_TABLE_BGCOLOR_DESCRIPTION'     => 'Pre-configured value: white',
    'INGENICO_TEMPLATE_TABLE_FONTCOLOR'               => 'Table text color:',
    'INGENICO_TEMPLATE_TABLE_FONTCOLOR_DESCRIPTION'   => 'Pre-configured value: black',
    'INGENICO_TEMPLATE_BUTTON_BGCOLOR'                => 'Button background color:',
    'INGENICO_TEMPLATE_BUTTON_BGCOLOR_DESCRIPTION'    => 'Pre-configured value:',
    'INGENICO_TEMPLATE_BUTTON_FONTCOLOR'              => 'Button text color:',
    'INGENICO_TEMPLATE_BUTTON_FONTCOLOR_DESCRIPTION'  => 'Pre-configured value: black',
    'INGENICO_TEMPLATE_FONTFAMILY'                    => 'Font:',
    'INGENICO_TEMPLATE_FONTFAMILY_DESCRIPTION'        => 'Pre-configured value: Verdana',
    'INGENICO_TEMPLATE_LOGO'                          => 'Your logo:',
    'INGENICO_TEMPLATE_LOGO_DESCRIPTION'              => 'URL/filename of the logo you want to display at the top of the payment page next to the title. The URL must be absolute (contain the full path), it cannot be relative.
The logo needs to be stored on a secure
server (https://www.yourshop.com). If
you do not have a secure environment to
store your image, you can send a JPG, PNG
or GIF file (and your PSPID) to
support@ingenico.com (only for production
accounts since this is a paying option! Please
activate the �Logo Hosting� option in your
Account > Options page before sending us
your logo).
If the logo is stored on our servers, you only
need to enter the filename, not the whole
URL.',
    'INGENICO_ALIAS'                                  => 'Alias Manager:',
    'INGENICO_ALIAS_MANAGER'                          => 'Activate Alias Manager:',
    'INGENICO_ALIAS_OPERATION'                        => 'Alias creation',
    'INGENICO_ALIAS_OPERATION_BYMERCHANT'             => 'configure',
    'INGENICO_ALIAS_OPERATION_BYINGENICO'                => 'automatic',
    'INGENICO_ALIAS_OPERATION_DESCRIPTION'            => 'Configure how the Alias will be created.<br />' .
    'Or let your Payment Service Provider create the Alias automatically<br />',
    'INGENICO_ALIAS_FORMAT'                           => 'Alias Format',
    'INGENICO_ALIAS_FORMAT_DESCRIPTION'               => 'Specify the following alias parameters:<br />' .
    "{shopname} = Your shop's name<br />" .
    "{firstname} = Customer's first name<br />" .
    "{lastname} = Customer's last name<br />" .
        '{customersid} = Customer ID<br />',
    'INGENICO_ALIAS_USAGE'                            => 'Alias description',
    'INGENICO_ALIAS_USAGE_DESCRIPTION'                => 'Short explanation for the customer on why you suggest the creation of an alias.',
    'MO_INGENICO__CAPTURE_CREDITCARD'                 => 'Capture credit card transaction',
    'MO_INGENICO__USE_UTF8'                           => 'UTF-8 encode',
    'MO_INGENICO__SET_OXPAID'                         => 'Set payment date',
    'MO_INGENICO__SET_OXPAID_HELP'                    => 'Sets the payment date, when the PSP returns an okay-state.<br />' .
        'ATTENTION: This state does not mean that the money was transfered to the shop account.',
    'MO_INGENICO__OXTRANSID_PARAM' => 'Which ID to store as OXTRANSID in OXORDER',
    'MO_INGENICO__OXTRANSID_PARAM_HELP' => '' .
        'Specifies which value should be stored as OXTRANSID in the table OXORDER:<br />' .
        'ORDERID: the ID generated by the module (e.g.: mo_ingenico_123456)<br />' .
        'PAYID: the ID generated by Ingenico',
    'MO_INGENICO__USE_HIDDEN_AUTH'                    => 'Hidden Authorization',
    'MO_INGENICO__USE_HIDDEN_AUTH_HELP'               => 'When using Hidden Authorization the user will not be redirected to the PSP to enter his payment information',
    'MO_INGENICO__USE_IFRAME_FOR_HIDDEN_AUTH' => 'via iFrame',
    'MO_INGENICO__USE_IFRAME_FOR_HIDDEN_AUTH_HELP' => '' .
        'Has no effect when hidden authorization is disabled<br />' .
        'true: the customer puts his card information in an iFrame of the psp (PCI konform)</br>' .
        'false: the customer puts his card information into a form on the shopsite that is sent to the psp (not PCI conform, PCI-Certificat necessary)',
    'INGENICO_SETUP_TITLE'                            => 'Setup',
    'MO_INGENICO__CAPTURE_CREDITCARD_HELP'            => 'Transaction type',
    'MO_INGENICO__USE_UTF8_HELP'                      => 'Send data Utf8-encoded. If you activate this option you have to change gateway-URLs to Utf8 gateway-URLs',
    'MO_INGENICO__API_USERID'                         => 'API User ID',
    'MO_INGENICO__API_USERID_HELP'                    => '',
    'MO_INGENICO__API_USERPASS'                       => 'API User\'s password',
    'MO_INGENICO__API_USERPASS_HELP'                  => '',
    'MO_INGENICO__SQL_INSTALL_ERROR_HEADER'           => 'The data base order could not be completed, please ask you data base admin to complete the data base order.',
    'MO_INGENICO__ORDER_OVERVIEW_STATUS'              => 'Ingenico Status',
    'MO_INGENICO__UNINSTALL_TPL_BLOCKS'               => 'Uninstall (Azure) Tpl-Blocks',
    'MO_INGENICO__INSTALL_TPL_BLOCKS'                 => 'Install (Azure) Tpl-Blocks',
);



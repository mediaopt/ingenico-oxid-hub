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
 * @package out
 * @copyright (C) Ogone 2009, 2010, 2011
 * @version 1.3
 * $Id: mo_ogone__lang.php 25 2013-05-24 15:26:26Z martin $
 */
$sLangName = "English";

// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
    'charset'                                      => 'ISO-8859-15',
// Admin Menu
    'mo_ogone'                                     => 'Ogone',
    'mo_ogone__setup'                              => 'Settings',
    'mo_ogone__interface'                          => 'Status overview',
    'mo_ogone__logfile'                            => 'Log',
// Admin -> Ogone -> Statusmeldungen
    'OGONE_DATE'                                   => 'Date',
    'OGONE_ORDERID'                                => 'Order ID',
    'OGONE_CUSTOMER_NAME'                          => 'Customer',
    'OGONE_CN'                                     => 'Card holder',
    'OGONE_AMOUNT'                                 => 'Amount',
    'OGONE_ACCEPTANCE'                             => 'Authorization',
    'OGONE_STATUS'                                 => 'Status',
    'OGONE_CARDNO'                                 => 'Card number',
    'OGONE_BRAND'                                  => 'Brand',
    'OGONE_ED'                                     => 'Expiry date',
    'OGONE_PAYID'                                  => 'Pay ID',
    'OGONE_NCERROR'                                => 'Error',
    'OGONE_TRXDATE'                                => 'Transaction date',
// Admin -> Ogone -> Einstellungen
    'HELP_OGONE_PSPID'                             => 'Please enter your (Test)-PSPID you have received from your Payment Service Provider.',
    'START_SETUP'                                  => 'Start installation',
    'UPDATE_SETUP'                                 => 'Update',
    'OGONE_PAYMENT_METHODS'                        => 'Please activate the payment methods<br />you use with your Payment Service Provider:',
    'OGONE_PAYMENT_METHODS_ALL_PAYMENT_METHODS'    => 'All payment methods',
    'MO_OGONE__GATEWAY_URLS'                       => 'Payment Gateway-URLs',
    'MO_OGONE__GATEWAY_URL_REDIRECT'               => 'Redirect',
    'MO_OGONE__GATEWAY_URL_ALIAS'                  => 'Alias',
    'MO_OGONE__GATEWAY_URL_ORDERDIRECT'            => 'Orderdirect',
// Zahlarten
    'MO_OGONE__PAYMENT_METHODS_CREDIT_CARD'        => 'Credit card (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_MAESTRO'            => 'Maestro (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_POST_CARD'          => 'Post Card (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_GIROPAY'            => 'giropay (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_SOFORTUEBERWEISUNG' => 'Sofort�berweisung.de (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_IDEAL'              => 'iDEAL (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_POST_EFINANCE'      => 'Post e-Finance (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_EPS'                => 'eps Austria (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_DEBIT_DE'           => 'Direct Debits DE (ELV) (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_DEBIT_NL'           => 'Direct Debits NL (Machtigingen) (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_PAYPAL'             => 'PayPal (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_MPASS'              => 'mPass (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_OPEN_INVOICE_DE'    => 'BillPay (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_MISTER_CASH'        => 'Mister Cash (Ogone)',
    'OGONE_PAYMENT_METHODS_AMEX'                   => 'American Express',
    'OGONE_PAYMENT_METHODS_VISA'                   => 'VISA',
    'OGONE_PAYMENT_METHODS_MASTERCARD'             => 'MasterCard',
    'OGONE_PAYMENT_METHODS_JCB'                    => 'JCB',
    'OGONE_PAYMENT_METHODS_BCMC'                   => 'Bancontact/Mister Cash',
    'OGONE_PSPID'                                  => 'Your PSPID:',
    'OGONE_USERID'                                 => 'Your USERID:',
    'OGONE_URL'                                    => 'Payment Gateway URL:',
    'PAYMENT_MAIN_REQUEST'                         => 'Payment Gateway call:',
    'PAYMENT_MAIN_REQUEST_IFRAME'                  => 'iFrame',
    'PAYMENT_MAIN_REQUEST_REDIRECT'                => 'Redirection',
    'HELP_PAYMENT_MAIN_REQUEST'                    => 'Payment page call',
    'OGONE_SECURE_KEY_IN'                          => 'SHA-IN Signature:',
    'OGONE_SECURE_KEY_OUT'                         => 'SHA-OUT Signature:',
    'OGONE_HASHING'                                => 'SHA Hashing:',
    'OGONE_HASHING_ALGORITHM'                      => 'Hash Algorithm:',
    'OGONE_HASHING_ALGORITHM_SHA_1'                => 'SHA-1',
    'OGONE_HASHING_ALGORITHM_SHA_256'              => 'SHA-256',
    'OGONE_HASHING_ALGORITHM_SHA_512'              => 'SHA-512',
    'OGONE_HASHING_ENCODING_1'                     => 'AUTO',
    'OGONE_HASHING_ENCODING_2'                     => 'ISO-8859-1',
    'OGONE_HASHING_ENCODING_3'                     => 'UTF-8',
    'OGONE_OPERATION'                              => 'Operation:',
    'OGONE_OPERATION_RES'                          => 'Authorization',
    'OGONE_OPERATION_SAL'                          => 'Direct sale',
    'OGONE_MODULE_DESCRIPTION'                     => 'For further information on the extension, please see the documentation and FAQ which you can find in the Support section of your Ogone account.',
    'OGONE_TEST_ACCOUNT'                           => 'Test account',
    'OGONE_PROD_ACCOUNT'                           => 'Production account',
    'OGONE_TEMPLATE'                               => 'Payment page layout:',
    'OGONE_TEMPLATE_TRUE'                          => 'Dynamic template',
    'OGONE_TEMPLATE_FALSE'                         => 'Static template',
    'OGONE_PMLISTSTYLE_TITLE'                      => 'Payment method layout:',
    'OGONE_PMLISTSTYLE_TITLE_DESCRIPTION'          => 'Arrange the list of payment methods on the payment page',
    'OGONE_PMLISTSTYLE_0'                          => 'Group logos horizontally with the group names on the left',
    'OGONE_PMLISTSTYLE_1'                          => 'Group logos horizontally without group names',
    'OGONE_PMLISTSTYLE_2'                          => 'Group logos vertically with name',
    'OGONE_BACK_TITLE'                             => 'Back button:',
    'OGONE_TEMPLATE_TITLE'                         => 'Title and Header of the payment page:',
    'OGONE_TEMPLATE_TITLE_DESCRIPTION'             => 'Pre-configured value:',
    'OGONE_TEMPLATE_BGCOLOR'                       => 'Background color:',
    'OGONE_TEMPLATE_BGCOLOR_DESCRIPTION'           => 'Pre-configured value: white',
    'OGONE_TEMPLATE_FONTCOLOR'                     => 'Text color:',
    'OGONE_TEMPLATE_FONTCOLOR_DESCRIPTION'         => 'Pre-configured value: black',
    'OGONE_TEMPLATE_TABLE_BGCOLOR'                 => 'Table background color:',
    'OGONE_TEMPLATE_TABLE_BGCOLOR_DESCRIPTION'     => 'Pre-configured value: white',
    'OGONE_TEMPLATE_TABLE_FONTCOLOR'               => 'Table text color:',
    'OGONE_TEMPLATE_TABLE_FONTCOLOR_DESCRIPTION'   => 'Pre-configured value: black',
    'OGONE_TEMPLATE_BUTTON_BGCOLOR'                => 'Button background color:',
    'OGONE_TEMPLATE_BUTTON_BGCOLOR_DESCRIPTION'    => 'Pre-configured value:',
    'OGONE_TEMPLATE_BUTTON_FONTCOLOR'              => 'Button text color:',
    'OGONE_TEMPLATE_BUTTON_FONTCOLOR_DESCRIPTION'  => 'Pre-configured value: black',
    'OGONE_TEMPLATE_FONTFAMILY'                    => 'Font:',
    'OGONE_TEMPLATE_FONTFAMILY_DESCRIPTION'        => 'Pre-configured value: Verdana',
    'OGONE_TEMPLATE_LOGO'                          => 'Your logo:',
    'OGONE_TEMPLATE_LOGO_DESCRIPTION'              => 'URL/filename of the logo you want to display at the top of the payment page next to the title. The URL must be absolute (contain the full path), it cannot be relative.
The logo needs to be stored on a secure
server (https://www.yourshop.com). If
you do not have a secure environment to
store your image, you can send a JPG, PNG
or GIF file (and your PSPID) to
support@ogone.com (only for production
accounts since this is a paying option! Please
activate the �Logo Hosting� option in your
Account > Options page before sending us
your logo).
If the logo is stored on our servers, you only
need to enter the filename, not the whole
URL.',
    'OGONE_ALIAS'                                  => 'Alias Manager:',
    'OGONE_ALIAS_MANAGER'                          => 'Activate Alias Manager:',
    'OGONE_ALIAS_OPERATION'                        => 'Alias creation',
    'OGONE_ALIAS_OPERATION_BYMERCHANT'             => 'configure',
    'OGONE_ALIAS_OPERATION_BYOGONE'                => 'automatic',
    'OGONE_ALIAS_OPERATION_DESCRIPTION'            => 'Configure how the Alias will be created.<br />' .
    'Or let your Payment Service Provider create the Alias automatically<br />',
    'OGONE_ALIAS_FORMAT'                           => 'Alias Format',
    'OGONE_ALIAS_FORMAT_DESCRIPTION'               => "Specify the following alias parameters:<br />" .
    "{shopname} = Your shop's name<br />" .
    "{firstname} = Customer's first name<br />" .
    "{lastname} = Customer's last name<br />" .
    "{customersid} = Customer ID<br />",
    'OGONE_ALIAS_USAGE'                            => 'Alias description',
    'OGONE_ALIAS_USAGE_DESCRIPTION'                => 'Short explanation for the customer on why you suggest the creation of an alias.',
    'MO_OGONE__CAPTURE_CREDITCARD'                 => 'Capture credit card transaction',
    'MO_OGONE__USE_UTF8'                           => 'UTF-8 encode',
    'OGONE_SETUP_TITLE'                            => 'Setup',
    'MO_OGONE__CAPTURE_CREDITCARD_HELP'            => 'Transaction type',
    'MO_OGONE__USE_UTF8_HELP'                      => 'Send data Utf8-encoded. If you activate this option you have to change gateway-URLs to Utf8 gateway-URLs',
    'MO_OGONE__API_USERID'                         => 'API User ID',
    'MO_OGONE__API_USERID_HELP'                    => '',
    'MO_OGONE__API_USERPASS'                       => "API User's password",
    'MO_OGONE__API_USERPASS_HELP'                  => '',
    'MO_OGONE__SQL_INSTALL_ERROR_HEADER'           => 'The data base order could not be completed, please ask you data base admin to complete the data base order.',
    'MO_OGONE__ORDER_OVERVIEW_STATUS'              => 'Ogone Status',
    'MO_OGONE__UNINSTALL_TPL_BLOCKS'               => 'Uninstall (Azure) Tpl-Blocks',
    'MO_OGONE__INSTALL_TPL_BLOCKS'                 => 'Install (Azure) Tpl-Blocks',
);



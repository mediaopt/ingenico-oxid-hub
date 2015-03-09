<?php

/**
 * $Id: config.php 57 2015-02-18 08:22:58Z mbe $ 
 */

define('MO_OGONE__PAYMENTTYPE_REDIRECT', 1);
define('MO_OGONE__PAYMENTTYPE_ONE_PAGE', 2);

// If set to true:
//  - hidden form fields are displayed as text-fields
$this->debug = false;

/**
 * ogone application id (Branding Param)
 * 
 * identify what version of the Oxid plugin the merchant is using
 *  This field can receive up to 10 characters, and should be formatted as followed : 2 characters for the Payment Service Provider (“OG” for Ogone, “CC” for Concardis) 
 * + 2 characters for the shopping cart (I suggest “OX” for Oxid) + 6 characters for the date of the release in format yymmdd
 *  For example, for a version of the plugin release on 26/01/2013, the ORIG for the Ogone version would be “OGOX130126” and for Concardis : “CCOX130126”
 */
$this->applicationId = '##appid##';

$this->moduleVersion = '2.2.1';

// Mapping from id to payment-method(pm) and brand
$this->paymentMethods = array(
    // credit card payments (one page)
    array(
        'oxid_payment_id' => 'ogone_credit_card',
        'pm'              => 'CreditCard',
        'brand'           => 'VISA',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
    array(
        'oxid_payment_id' => 'ogone_credit_card',
        'pm'              => 'CreditCard',
        'brand'           => 'MasterCard',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
    array(
        'oxid_payment_id' => 'ogone_credit_card',
        'pm'              => 'CreditCard',
        'brand'           => 'American Express',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
    array(
        'oxid_payment_id' => 'ogone_credit_card',
        'pm'              => 'CreditCard',
        'brand'           => 'JCB',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
    array(
        'oxid_payment_id' => 'ogone_credit_card',
        'pm'              => 'CreditCard',
        'brand'           => 'Diners Club',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
    // debit card payments (redirect)
    array(
        'oxid_payment_id' => 'ogone_maestro',
        'pm'              => 'CreditCard',
        'brand'           => 'Maestro',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    array(
        'oxid_payment_id' => 'ogone_post_card',
        'pm'              => 'PostFinance Card',
        'brand'           => 'PostFinance + card',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    // direct banking payments (redirect)
    array(
        'oxid_payment_id' => 'ogone_giropay',
        'pm'              => 'giropay',
        'brand'           => 'giropay',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    array(
        'oxid_payment_id' => 'ogone_sofortueberweisung',
        'pm'              => 'DirectEbanking',
        'brand'           => 'Sofort Uberweisung',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    array(
        'oxid_payment_id' => 'ogone_ideal',
        'pm'              => 'iDEAL',
        'brand'           => 'iDEAL',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    array(
        'oxid_payment_id' => 'ogone_post_efinance',
        'pm'              => 'PostFinance e-finance',
        'brand'           => 'PostFinance e-finance',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    array(
        'oxid_payment_id' => 'ogone_eps',
        'pm'              => 'EPS',
        'brand'           => 'EPS',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    // direct debit payments (one-page, but at the moment only redirect is supported)
    array(
        'oxid_payment_id' => 'ogone_debit_de',
        'pm'              => 'Direct Debits DE',
        'brand'           => 'Direct Debits DE',
        //~ 'paymenttype' => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    array(
        'oxid_payment_id' => 'ogone_debit_nl',
        'pm'              => 'Direct Debits NL',
        'brand'           => 'Direct Debits NL',
        //~ 'paymenttype' => MO_OGONE__PAYMENTTYPE_ONE_PAGE),
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    // e-Wallet payments (redirect)
    array(
        'oxid_payment_id' => 'ogone_paypal',
        'pm'              => 'PAYPAL',
        'brand'           => 'PAYPAL',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    // Mobile payments (redirect)
    array(
        'oxid_payment_id' => 'ogone_mpass',
        'pm'              => 'MPASS',
        'brand'           => 'MPASS',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    // BillPay (redirect)
    array(
        'oxid_payment_id' => 'ogone_open_invoice_de',
        'pm'              => 'Open Invoice DE',
        'brand'           => 'Open Invoice DE',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
    // Mister Cash (redirect)
    array(
        'oxid_payment_id' => 'ogone_mister_cash',
        'pm'              => 'CreditCard',
        'brand'           => 'BCMC',
        'paymenttype'     => MO_OGONE__PAYMENTTYPE_REDIRECT),
);


/**
 * declare all known response parameter for sha out verification
 */
$this->shaOutParameters = array(
    'AAVADDRESS',
    'AAVCHECK',
    'AAVMAIL',
    'AAVNAME',
    'AAVPHONE',
    'AAVZIP',
    'ACCEPTANCE',
    'ALIAS',
    'AMOUNT',
    'BIC',
    'BIN',
    'BRAND',
    'CARDNO',
    'CCCTY',
    'CN',
    'COLLECTOR_BIC',
    'COLLECTOR_IBAN',
    'COMPLUS',
    'CREATION_STATUS',
    'CREDITDEBIT',
    'CURRENCY',
    'CVCCHECK',
    'DCC_COMMPERCENTAGE',
    'DCC_CONVAMOUNT',
    'DCC_CONVCCY',
    'DCC_EXCHRATE',
    'DCC_EXCHRATESOURCE',
    'DCC_EXCHRATETS',
    'DCC_INDICATOR',
    'DCC_MARGINPERCENTAGE',
    'DCC_VALIDHOURS',
    'DIGESTCARDNO',
    'ECI',
    'ED',
    'EMAIL',
    'ENCCARDNO',
    'FXAMOUNT',
    'FXCURRENCY',
    'IP',
    'IPCTY',
    'MANDATEID',
    'MOBILEMODE',
    'NBREMAILUSAGE',
    'NBRIPUSAGE',
    'NBRIPUSAGE_ALLTX',
    'NBRUSAGE',
    'NCERROR',
    'ORDERID',
    'PAYID',
    'PAYMENT_REFERENCE',
    'PM',
    'SCO_CATEGORY',
    'SCORING',
    'SEQUENCETYPE',
    // 'SHASIGN',  exclude shasign from parameter list and from sha hashing
    'SIGNDATE',
    'STATUS',
    'SUBBRAND',
    'SUBSCRIPTION_ID',
    'TRXDATE',
    'VC'
);


$this->supportedCurrencies = array('AED', 'ANG', 'ARS', 'AUD', 'AWG', 'BGN', 'BRL', 'BYR', 'CAD', 'CHF', 'CNY', 'CZK', 'DKK', 'EEK', 'EGP', 'EUR', 'GBP', 'GEL', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'KRW', 'LTL', 'LVL', 'MAD', 'MXN', 'NOK', 'NZD', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'SKK', 'THB', 'TRY', 'UAH', 'USD', 'XAF', 'XOF', 'XPF', 'ZAR');


$this->oxidLangCodeToOgoneLanguageCountryCode = array(
    // mapping country code to language_Country
    'en' => 'en_US', // (English)
    'ar' => 'ar_AR', // (Arabic)
    'cz' => 'cs_CZ', // (Czech)
    'dk' => 'da_DK', // (Danish)
    'de' => 'de_DE', // (German)
    'gr' => 'el_GR', // (Greek)
    'es' => 'es_ES', // (Spanish)
    'fr' => 'fr_FR', // (French)
    'hu' => 'hu_HU', // (hungarian)
    'it' => 'it_IT', // (Italian)
    'jp' => 'ja_JP', // (Japanese)
    'be' => 'nl_BE', // (Flemish)
    'nl' => 'nl_NL', // (Dutch)
    'no' => 'no_NO', // (Norwegian)
    'pl' => 'pl_PL', // (Polish)
    'pt' => 'pt_PT', // (Portugese)
    'ru' => 'ru_RU', // (Russian)
    'se' => 'se_SE', // (Swedish)
    'sk' => 'sk_SK', // (Slovak)
    'tr' => 'tr_TR', // (Turkish)
    // mapping language code to language_Country
    'cs' => 'cs_CZ', // (Czech)
    'da' => 'da_DK', // (Danish)
    'el' => 'el_GR', // (Greek)
    'ja' => 'ja_JP', // (Japanese)
);

//From docs:
//Zeitüberschreitung für die Transaktion (in Sekunden, Wert zwischen 30 und 90).
//WICHTIG: Der hier angegebene Wert muss kleiner als der Zeitüberschreitungswert in Ihrem System sein!
$this->rtimeout = 15;

$this->maximumLogfileSizeInBytes = 1024 * 1024;

//$this->logLevels = array('CALL', 'INFO', 'ERROR');
$this->logLevels = array('ERROR');

$this->logTableCreateSql =
        "CREATE TABLE `mo_ogone__payment_logs` (
    `OXID` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
    `id` int NOT NULL auto_increment,

    `orderID` varchar(30) NOT NULL default '',
    `transID` varchar(64) UNIQUE NOT NULL default '',
    `amount` double NOT NULL default '0',
    `currency` varchar(3) NOT NULL default '',
    `language` varchar(5) NOT NULL default '',
    `PM` varchar(25) NOT NULL default '',
    `BRAND` varchar(25) NOT NULL default '',
    `CARDNO` varchar(21) NOT NULL default '',
    `Alias` varchar(50) NOT NULL default '',
    `CN` varchar(35) NOT NULL default '',
    `ED` varchar(7) NOT NULL default '',
    `ACCEPTANCE` varchar(15) NOT NULL default '',
    `STATUS` int(2) NOT NULL default '0',
    `TRXDATE` varchar(32) NOT NULL default '',
    `NCERROR` int(8) NOT NULL default '0',
    `NCERRORPLUS` varchar(255) NOT NULL default '',
    `NCSTATUS` varchar(4) NOT NULL default '',
    `CVCCHECK` varchar(2) NOT NULL default '',
    `AAVCHECK` varchar(2) NOT NULL default '',
    `ECI` int(1) NOT NULL default '0',
    `VC` varchar(3) NOT NULL default '',
    `SCORING` varchar(4) NOT NULL default '',
    `SCO_CATEGORY` varchar(1) NOT NULL default '',
    `PAYID` int(9) NOT NULL default '0',
    `PAYIDSUB` int(3) NOT NULL default '0',
    `SHASIGN` varchar(40) NOT NULL default '',
    `SESSION_ID` varchar(255) NOT NULL default '',
    `IP` varchar(32) NOT NULL default '',
    `IPCTY` varchar(2) NOT NULL default '',
    `CCCTY` varchar(2) NOT NULL default '',

    `BILLFNAME` VARCHAR(255) NOT NULL default '',
    `BILLLNAME` VARCHAR(255) NOT NULL default '',
    `date` timestamp NOT NULL,

    PRIMARY KEY (id)
  )";
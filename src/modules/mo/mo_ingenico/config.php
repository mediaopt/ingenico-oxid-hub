<?php

/**
 * internal configuration
 */

define('MO_INGENICO__PAYMENTTYPE_REDIRECT', 1);
define('MO_INGENICO__PAYMENTTYPE_ONE_PAGE', 2);

/**
 * ingenico application id (Branding Param)
 *
 * identify what version of the Oxid plugin the merchant is using
 *  This field can receive up to 10 characters, and should be formatted as followed : 2 characters for the Payment Service Provider (“OG” for Ingenico, “CC” for Concardis)
 * + 2 characters for the shopping cart (I suggest “OX” for Oxid) + 6 characters for the date of the release in format yymmdd
 *  For example, for a version of the plugin release on 26/01/2013, the ORIG for the Ingenico version would be “OGOX130126” and for Concardis : “CCOX130126”
 */
$this->applicationId = 'INOX170524';

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
    'DEVICEID',
    'DIGESTCARDNO',
    'ECI',
    'ED',
    'EMAIL',
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
    'PAYIDSUB',
    'PAYLIBIDREQUEST',
    'PAYLIBTRANSID',
    'PAYMENT_REFERENCE',
    'PM',
    'SCORING',
    'SCO_CATEGORY',
    'SEQUENCETYPE',
    'SIGNDATE',
    'STATUS',
    'SUBBRAND',
    'SUBSCRIPTION_ID',
    'TICKET',
    'TRXDATE',
    'VC',
    'WALLET',
);


$this->shaOutParametersAliasGateway = array(
    'ALIAS',
    'BIC',
    'BRAND',
    'CARDNO',
    'CN',
    'CVC',
    'ED',
    'NCERROR',
    'NCERRORCARDNO',
    'NCERRORCN',
    'NCERRORCVC',
    'NCERRORED',
    'ORDERID',
    'STATUS',
    'STOREPERMANENTLY'
);

$this->shaOutParametersHostedTokenization = array(
    'ALIAS',
    'ALIAS_ALIASID',
    'ALIAS_NCERROR',
    'ALIAS_NCERRORCARDNO',
    'ALIAS_NCERRORCN',
    'ALIAS_NCERRORCVC',
    'ALIAS_NCERRORED',
    'ALIAS_ORDERID',
    'ALIAS_STATUS',
    'ALIAS_STOREPERMANENTLY',
    'BIC',
    'BRAND',
    'CARD_BIC',
    'CARD_BIN',
    'CARD_BRAND',
    'CARD_CARDHOLDERNAME',
    'CARD_CARDNUMBER',
    'CARD_CVC',
    'CARD_EXPIRYDATE',
    'CARDNO',
    'CN',
    'CVC',
    'ED',
    'NCERROR',
    'NCERRORCARDNO',
    'NCERRORCN',
    'NCERRORCVC',
    'NCERRORED',
    'ORDERID',
    'STATUS',
    'STOREPERMANENTLY',
    'TICKET',
    'WALLET',
);

$this->supportedCurrencies = array('AED', 'ANG', 'ARS', 'AUD', 'AWG', 'BGN', 'BRL', 'BYR', 'CAD', 'CHF', 'CNY', 'CZK', 'DKK', 'EEK', 'EGP', 'EUR', 'GBP', 'GEL', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'KRW', 'LTL', 'LVL', 'MAD', 'MXN', 'NOK', 'NZD', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'SKK', 'THB', 'TRY', 'UAH', 'USD', 'XAF', 'XOF', 'XPF', 'ZAR');


$this->oxidLangCodeToIngenicoLanguageCountryCode = array(
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

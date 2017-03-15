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
 * @copyright (C) Ogone 2009, 2010
 * @version 1.3
 * $Id: mo_ogone__lang.php 25 2013-05-24 15:26:26Z martin $
 */
$sLangName = 'Deutsch';

// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
    'charset' => 'UTF-8',

    //API config
    'SHOP_MODULE_GROUP_moOgoneApiConfiguration' => 'API Konfiguration',
    'SHOP_MODULE_mo_ogone__isLiveMode' => 'Live Modus',
    'HELP_SHOP_MODULE_mo_ogone__isLiveMode' => 'Zum Testen sollte die Option deaktiviert sein. '
        . 'Für den Produktivbetrieb aktivieren Sie die Option.',
    'SHOP_MODULE_mo_ogone__logLevel' => 'Log Level',
    'HELP_SHOP_MODULE_mo_ogone__logLevel' => 'Konfigurieren Sie hier in welchem Umfang Logeinträge geschrieben werden',
    'SHOP_MODULE_mo_ogone__logLevel_DEBUG' => 'Debug',
    'SHOP_MODULE_mo_ogone__logLevel_INFO' => 'Info',
    'SHOP_MODULE_mo_ogone__logLevel_ERROR' => 'Error',
    'SHOP_MODULE_mo_ogone__capture_creditcard' => 'Kreditkartentransaktionen reservieren',
    'HELP_SHOP_MODULE_mo_ogone__capture_creditcard' => 'Ist die Option deaktiviert, werden die Beträge sofort eingezogen.',
    'SHOP_MODULE_mo_ogone__use_utf8' => 'UTF-8 encode',
    'HELP_SHOP_MODULE_mo_ogone__use_utf8' => 'Daten UTF8-kodiert an übertragen. Die Einstellung muss mit der Konfiguration im Ogone Backend übereinstimmen. Konfiguration -> Technische Informationen -> Globale Sicherheitsparameter',
    'SHOP_MODULE_mo_ogone__set_oxpaid' => 'Bezahldatum setzen',
    'HELP_SHOP_MODULE_mo_ogone__set_oxpaid' => 'Setzt das Bezahldatum, wenn vom PSP ein OK-Status zurück kommt.<br />' .
        'ACHTUNG: Dieser Status bedeutet nicht, dass das Geld bei Ihrem Shop eingegangen ist.',
    'SHOP_MODULE_mo_ogone__use_hidden_auth' => 'Hidden Authorization',
    'HELP_SHOP_MODULE_mo_ogone__use_hidden_auth' => 'Bei der Verwendung von Hidden Authorization verlässt der Nutzer die Shopseite bei eintragen seiner Zahlungsinformationen nicht',
    'SHOP_MODULE_mo_ogone__use_iframe' => 'via iFrame',
    'HELP_SHOP_MODULE_mo_ogone__use_iframe' => '' .
        'Hat keinen Effekt, wenn Hidden Authorization deaktiviert ist<br />' .
        'Wahr: Zur Hidden Authorization wird ein iFrame des PSP verwendet (PCI konform)</br>' .
        'Falsch: Die Daten werden in einem Formular der Shopseite eingetragen, aber direkt an den PSP gesendet (nicht PCI konform, PCI-Zertifikat notwendig)',
    'SHOP_MODULE_mo_ogone__transid_param' => 'In Order zu speichernde TransID',
    'HELP_SHOP_MODULE_mo_ogone__transid_param' => '' .
        'Gibt an, welcher Wert als OXTRANSID in der Tabelle OXORDER gespeichert werden soll:<br />' .
        'ORDERID: die vom Modul erstellte ID (z.b.: mo_ogone_123456)<br />' .
        'PAYID: die von Ogone erstellte ID',
    'SHOP_MODULE_mo_ogone__transid_param_ORDERID' => 'ORDERID',
    'SHOP_MODULE_mo_ogone__transid_param_PAYID' => 'PAYID',
    'SHOP_MODULE_ogone_sPSPID' => 'Ihre PSPID',
    'HELP_SHOP_MODULE_ogone_sPSPID' => 'Tragen Sie hier Ihre (Test)-PSPID ein, die Sie vom Payment Service Provider erhalten haben.',
    'SHOP_MODULE_mo_ogone__api_userid' => 'User-ID des Ogone API-Nutzers',
    'HELP_SHOP_MODULE_mo_ogone__api_userid' => 'Die API Nuzer können im Ogone Backend verwaltet werden. Konfiguration -> Benutzerverwaltung.',
    'SHOP_MODULE_mo_ogone__api_userpass' => 'Passwort des Ogone API-Nutzers',
    'HELP_SHOP_MODULE_mo_ogone__api_userpass' => 'Die Passwörter für API Nuzer können im Ogone Backend verwaltet werden. Konfiguration -> Benutzerverwaltung.',
    'SHOP_MODULE_ogone_sHashingAlgorithm' => 'SHA Hashing',
    'HELP_SHOP_MODULE_ogone_sHashingAlgorithm' => 'Mit welchem Algorithmus wird der SHA Wert berechnet. Die Einstellung muss mit der Konfiguration im Ogone Backend übereinstimmen. Konfiguration -> Technische Informationen -> Globale Sicherheitsparameter',
    'SHOP_MODULE_ogone_sHashingAlgorithm_SHA-1' => 'SHA-1',
    'SHOP_MODULE_ogone_sHashingAlgorithm_SHA-256' => 'SHA-256',
    'SHOP_MODULE_ogone_sHashingAlgorithm_SHA-512' => 'SHA-512',
    'SHOP_MODULE_ogone_sSecureKeyIn' => 'SHA-In Signatur',
    'HELP_SHOP_MODULE_ogone_sSecureKeyIn' => 'Schlüssel für die Berechnung des SHA Wertes für ausgehende Anfragen. Die Einstellung muss mit der Konfiguration im Ogone Backend übereinstimmen. Konfiguration -> Technische Informationen -> Daten- und Ursprungsüberprüfung',
    'SHOP_MODULE_ogone_sSecureKeyOut' => 'SHA-Out Signatur',
    'HELP_SHOP_MODULE_ogone_sSecureKeyOut' => 'Schlüssel für die Berechnung des SHA Wertes für eingehende Anfragen. Die Einstellung muss mit der Konfiguration im Ogone Backend übereinstimmen. Konfiguration -> Technische Informationen -> Transaktions-Feedback',
    'SHOP_MODULE_mo_ogone__timeout' => 'Timout für die API Kommunikation',
    'HELP_SHOP_MODULE_mo_ogone__timeout' => 'Zeitüberschreitung für die Transaktion in Sekunden. WICHTIG: Der hier angegebene Wert sollte nicht größer als der Zeitüberschreitungswert in Ihrem System sein!',
    'SHOP_MODULE_mo_ogone__debug_mode' => 'Debug Modus',
    'HELP_SHOP_MODULE_mo_ogone__debug_mode' => 'Wenn aktiviert, werden zusätzliche Informationen im Frontend ausgegeben. Im Produktivbetrieb unbedingt deaktivieren',

    //payment page layout config
    'SHOP_MODULE_GROUP_moOgoneLayoutPaymentPage' => 'Layout der Zahlungsseite',
    'SHOP_MODULE_ogone_sTemplate' => 'Dynamische Vorlage verwenden',
    'HELP_SHOP_MODULE_ogone_sTemplate' => 'Ist die Option deaktiviert, wird eine statische Vorlage verwendet.',
    'SHOP_MODULE_ogone_sTplPMListStyle' => 'Layout der Zahlungsmethoden',
    'HELP_SHOP_MODULE_ogone_sTplPMListStyle' => 'Die Liste der Zahlungsmethoden auf der Zahlungsseite gestalten',
    'SHOP_MODULE_ogone_sTplPMListStyle_0' => 'Horizontal gruppierte Logos mit dem Gruppennamen links davon',
    'SHOP_MODULE_ogone_sTplPMListStyle_1' => 'Horizontal gruppierte Logos ohne Gruppennamen',
    'SHOP_MODULE_ogone_sTplPMListStyle_2' => 'Vertikale Liste von Logos mit Name',
    'SHOP_MODULE_ogone_blBackButton' => 'Zurück Schaltfläche',
    'HELP_SHOP_MODULE_ogone_blBackButton' => 'Soll ein "Zurück" Button eingeblendet werden?',
    'SHOP_MODULE_ogone_blTplTitle' => 'Titel und Kopfzeile der Zahlungseite',
    'HELP_SHOP_MODULE_ogone_blTplTitle' => 'Ist diese Option aktiv, wird der Inhalt des CMS Snippets "mo_ogone_tplTitle" als Titel der Zahlungsseite verwendet.',
    'SHOP_MODULE_ogone_sTplBGColor' => 'Hintergrundfarbe',
    'HELP_SHOP_MODULE_ogone_sTplBGColor' => 'Voreingestellter Wert: white',
    'SHOP_MODULE_ogone_sTplFontColor' => 'Textfarbe',
    'HELP_SHOP_MODULE_ogone_sTplFontColor' => 'Voreingestellter Wert: black',
    'SHOP_MODULE_ogone_sTplTableBGColor' => 'Farbe des Tabellenhintergrundes',
    'HELP_SHOP_MODULE_ogone_sTplTableBGColor' => 'Voreingestellter Wert: white',
    'SHOP_MODULE_ogone_sTplTbFontColor' => 'Farbe des Tabellentextes',
    'HELP_SHOP_MODULE_ogone_sTplTbFontColor' => 'Voreingestellter Wert: black',
    'SHOP_MODULE_ogone_sTplBtnBGColor' => 'Hintergrundfarbe von Schaltflächen',
    'HELP_SHOP_MODULE_ogone_sTplBtnBGColor' => 'Voreingestellter Wert: -',
    'SHOP_MODULE_ogone_sTplBtnFontColor' => 'Textfarbe von Schaltflächen',
    'HELP_SHOP_MODULE_ogone_sTplBtnFontColor' => 'Voreingestellter Wert: black',
    'SHOP_MODULE_ogone_sTplFontFamily' => 'Schriftart',
    'HELP_SHOP_MODULE_ogone_sTplFontFamily' => 'Voreingestellter Wert: Verdana',
    'SHOP_MODULE_ogone_sTplLogo' => 'Ihr Logo',
    'HELP_SHOP_MODULE_ogone_sTplLogo' => 'URL/Dateiname des Logos, welches oben auf der Zahlungsseite neben dem Titel erscheinen soll. Die URL muss absolut sein. Das Logo muss auf einem sicheren Server gespeichert sein (https://www.ihrShop.de). Wenn Sie über keine sichere Umgebung zur Speicherung des Bildes verfügen, können Sie eine JPG- oder GIF-Datei (und Ihre PSPID) an support@ogone.com senden. Dies ist nur für reguläre Produktiv-Konten möglich, weil kostenpflichtig. Bevor Sie uns das Logo schicken, aktivieren Sie bitte die Option "Logo Hosting" in Ihrem Produktiv-Konto unter Konfiguration > Konto >Ihre Optionen. Wenn das Logo auf unserem Server gespeichert ist, müssen Sie nur den Dateinamen angeben, nicht die komplette URL.',

// Admin Menu
    'mo_ogone' => 'Ogone',
    'mo_ogone__setup' => 'Einstellungen',
    'mo_ogone__interface' => 'Statusmeldungen',
    'mo_ogone__logfile' => 'Log',
// Admin -> Ogone -> Statusmeldungen
    'OGONE_DATE' => 'Datum',
    'OGONE_ORDERID' => 'Best.Nr.',
    'OGONE_TRANSID' => 'Trans.Nr.',
    'OGONE_CUSTOMER_NAME' => 'Kunde',
    'OGONE_CN' => 'Halter',
    'OGONE_AMOUNT' => 'Betrag',
    'OGONE_ACCEPTANCE' => 'Genehmigung',
    'OGONE_STATUS' => 'Status',
    'OGONE_CARDNO' => 'Nummer',
    'OGONE_BRAND' => 'Brand',
    'OGONE_ED' => 'Gültigk.',
    'OGONE_PAYID' => 'Pay ID',
    'OGONE_NCERROR' => 'Fehler',
    'OGONE_TRXDATE' => 'Trans.Zeit',
// Admin -> Ogone -> Einstellungen
    'MO_OGONE__ISLIVEMODE' => 'Live-Modus',
    'MO_OGONE_LOGLEVEL' => 'Log-Level',
    'MO_OGONE_LOGLEVEL_DEBUG' => 'Debug',
    'MO_OGONE_LOGLEVEL_INFO' => 'Info',
    'MO_OGONE_LOGLEVEL_ERROR' => 'Error',
    'HELP_OGONE_PSPID' => 'Tragen Sie hier Ihre (Test)-PSPID ein, die Sie vom Payment Service Provider erhalten haben.',
    'START_SETUP' => 'Installation beginnen',
    'UPDATE_SETUP' => 'Aktualisieren',
    'OGONE_PAYMENT_METHODS' => 'Bitte übertragen Sie hier Ihre<br />bei Ogone freigeschalteten Zahlungsarten:',
    'OGONE_PAYMENT_METHODS_ALL_PAYMENT_METHODS' => 'Alle Zahlungsarten',
// Zahlarten
    'MO_OGONE__PAYMENT_METHODS_CREDIT_CARD' => 'Kreditkarte (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_MAESTRO' => 'Maestro (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_POST_CARD' => 'PostFinance Card (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_GIROPAY' => 'giropay (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_SOFORTUEBERWEISUNG' => 'Sofortüberweisung.de (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_IDEAL' => 'iDEAL (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_POST_EFINANCE' => 'PostFinance E-Finance (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_EPS' => 'eps Österreich (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_DEBIT_DE' => 'Lastschrift DE (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_DEBIT_NL' => 'Lastschrift NL (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_PAYPAL' => 'PayPal (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_MPASS' => 'mPass (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_OPEN_INVOICE_DE' => 'BillPay (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_MISTER_CASH' => 'Mister Cash (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_VVV' => 'VVV Gift Card (Ogone)',
    'MO_OGONE__PAYMENT_METHODS_DANKORT' => 'Dankort (Ogone)',
    'OGONE_PAYMENT_METHODS_AMEX' => 'American Express',
    'OGONE_PAYMENT_METHODS_VISA' => 'VISA',
    'OGONE_PAYMENT_METHODS_MASTERCARD' => 'MasterCard',
    'OGONE_PAYMENT_METHODS_JCB' => 'JCB',
    'OGONE_PAYMENT_METHODS_BCMC' => 'Bancontact/Mister Cash',
    'OGONE_PSPID' => 'Ihre PSPID:',
    'OGONE_USERID' => 'Ihre USERID:',
    'OGONE_URL' => 'URL der Bezahlplattform:',
    'PAYMENT_MAIN_REQUEST' => 'Aufruf der Bezahlplattform:',
    'PAYMENT_MAIN_REQUEST_IFRAME' => 'iFrame',
    'PAYMENT_MAIN_REQUEST_REDIRECT' => 'Weiterleitung',
    'HELP_PAYMENT_MAIN_REQUEST' => 'Aufruf zur Bezahlseite',
    'OGONE_SECURE_KEY_IN' => 'SHA-In Signatur:',
    'OGONE_SECURE_KEY_OUT' => 'SHA-Out Signatur:',
    'OGONE_HASHING' => 'SHA Hashing:',
    'OGONE_HASHING_ALGORITHM' => 'Hash-Algorithmus:',
    'OGONE_HASHING_ALGORITHM_SHA_1' => 'SHA-1',
    'OGONE_HASHING_ALGORITHM_SHA_256' => 'SHA-256',
    'OGONE_HASHING_ALGORITHM_SHA_512' => 'SHA-512',
    'OGONE_HASHING_ENCODING_1' => 'AUTO',
    'OGONE_HASHING_ENCODING_2' => 'ISO-8859-1',
    'OGONE_HASHING_ENCODING_3' => 'UTF-8',
    'OGONE_OPERATION' => 'Operation:',
    'OGONE_OPERATION_RES' => 'Autorisierung',
    'OGONE_OPERATION_SAL' => 'Direktbuchung',
    'OGONE_MODULE_DESCRIPTION' => 'Weitere Informationen zu den Zahlungsmodulen entnehmen Sie bitte der Dokumentation und dem F.A.Q., die Sie in dem Support Bereich Ihres Ogone-Konto finden.',
    'OGONE_TEST_ACCOUNT' => 'Testkonto',
    'OGONE_PROD_ACCOUNT' => 'Produktivkonto',
    'OGONE_TEMPLATE' => 'Layout der Zahlungsseite:',
    'OGONE_TEMPLATE_TRUE' => 'Dynamische Vorlage',
    'OGONE_TEMPLATE_FALSE' => 'Statische Vorlage',
    'OGONE_PMLISTSTYLE_TITLE' => 'Layout der Zahlungsmethoden:',
    'OGONE_PMLISTSTYLE_TITLE_DESCRIPTION' => 'Die Liste der Zahlungsmethoden auf der Zahlungsseite gestalten',
    'OGONE_PMLISTSTYLE_0' => 'Horizontal gruppierte Logos mit dem Gruppennamen links davon',
    'OGONE_PMLISTSTYLE_1' => 'Horizontal gruppierte Logos ohne Gruppennamen',
    'OGONE_PMLISTSTYLE_2' => 'Vertikale Liste von Logos mit Name',
    'OGONE_BACK_TITLE' => 'Zurück-Schaltfläche:',
    'OGONE_TEMPLATE_TITLE' => 'Titel und Kopfzeile der Zahlungseite:',
    'OGONE_TEMPLATE_TITLE_DESCRIPTION' => 'Voreingestellter Wert:',
    'OGONE_TEMPLATE_BGCOLOR' => 'Hintergrundfarbe:',
    'OGONE_TEMPLATE_BGCOLOR_DESCRIPTION' => 'Voreingestellter Wert: white',
    'OGONE_TEMPLATE_FONTCOLOR' => 'Textfarbe:',
    'OGONE_TEMPLATE_FONTCOLOR_DESCRIPTION' => 'Voreingestellter Wert: black',
    'OGONE_TEMPLATE_TABLE_BGCOLOR' => 'Farbe des Tabellenhintergrundes:',
    'OGONE_TEMPLATE_TABLE_BGCOLOR_DESCRIPTION' => 'Voreingestellter Wert: white',
    'OGONE_TEMPLATE_TABLE_FONTCOLOR' => 'Farbe des Tabellentextes:',
    'OGONE_TEMPLATE_TABLE_FONTCOLOR_DESCRIPTION' => 'Voreingestellter Wert: black',
    'OGONE_TEMPLATE_BUTTON_BGCOLOR' => 'Hintergrundfarbe von Schaltflächen:',
    'OGONE_TEMPLATE_BUTTON_BGCOLOR_DESCRIPTION' => 'Voreingestellter Wert:',
    'OGONE_TEMPLATE_BUTTON_FONTCOLOR' => 'Textfarbe von Schaltflächen:',
    'OGONE_TEMPLATE_BUTTON_FONTCOLOR_DESCRIPTION' => 'Voreingestellter Wert: black',
    'OGONE_TEMPLATE_FONTFAMILY' => 'Schriftart:',
    'OGONE_TEMPLATE_FONTFAMILY_DESCRIPTION' => 'Voreingestellter Wert: Verdana',
    'OGONE_TEMPLATE_LOGO' => 'Ihr Logo:',
    'OGONE_TEMPLATE_LOGO_DESCRIPTION' => 'URL/Dateiname des Logos, das oben auf der Zahlungsseite neben dem Titel erscheinen soll. Die URL muss absolut sein. Das Logo muss auf einem sicheren Server gespeichert sein (https://www.ihrShop.de). Wenn Sie über keine sichere Umgebung zur Speicherung des Bildes verfügen, können Sie eine JPG- oder GIF-Datei (und Ihre PSPID) an support@ogone.com senden. Dies ist nur für reguläre Produktiv-Konten möglich, weil kostenpflichtig. Bevor Sie uns das Logo schicken, aktivieren Sie bitte die Option "Logo Hosting" in Ihrem Produktiv-Konto unter Konfiguration > Konto >Ihre Optionen. Wenn das Logo auf unserem Server gespeichert ist, müssen Sie nur den Dateinamen angeben, nicht die komplette URL.',
    'OGONE_ALIAS' => 'Alias Manager:',
    'OGONE_ALIAS_MANAGER' => 'Alias Manager aktivieren',
    'OGONE_ALIAS_OPERATION' => 'Vergabe des Alias',
    'OGONE_ALIAS_OPERATION_BYMERCHANT' => 'konfigurieren',
    'OGONE_ALIAS_OPERATION_BYOGONE' => 'automatisch',
    'OGONE_ALIAS_OPERATION_DESCRIPTION' => 'Konfigurieren Sie mit der nachfolgenden Einstellung wie der Alias-Name<br />' .
    'generiert wird. Oder lassen Sie den Alias-Namen automatisch durch Ogone<br />' .
    'vergeben.',
    'OGONE_ALIAS_FORMAT' => 'Alias-Format',
    'OGONE_ALIAS_FORMAT_DESCRIPTION' => 'Formatierung des Aliasnamen mittels folgender Parameter:<br />' .
    '{shopname} = Name Ihres Shops<br />' .
    '{firstname} = Vorname des Kunden<br />' .
    '{lastname} = Nachname des Kunden<br />' .
    '{customersid} = Kundennumer<br />',
    'OGONE_ALIAS_USAGE' => 'Alias-Beschreibung',
    'OGONE_ALIAS_USAGE_DESCRIPTION' => 'Beschreibung die dem Kunden als Grund für die Alias-Registrierung angezeigt wird.',
    'MO_OGONE__CAPTURE_CREDITCARD' => 'Kreditkartentransaktionen reservieren',
    'MO_OGONE__SET_OXPAID' => 'Bezahldatum setzen',
    'MO_OGONE__SET_OXPAID_HELP' => 'Setzt das Bezahldatum, wenn vom PSP ein OK-Status zurück kommt.<br />' .
        'ACHTUNG: Dieser Status bedeutet nicht, dass das Geld bei Ihrem Shop eingegangen ist.',
    'MO_OGONE__USE_UTF8' => 'UTF-8 encode',
    'MO_OGONE__USE_HIDDEN_AUTH' => 'Hidden Authorization',
    'MO_OGONE__USE_HIDDEN_AUTH_HELP' => 'Bei der Verwendung von Hidden Authorization verlässt der Nutzer die Shopseite bei eintragen seiner Zahlungsinformationen nicht',
    'MO_OGONE__OXTRANSID_PARAM' => 'In Order zu speichernde TransID',
    'MO_OGONE__OXTRANSID_PARAM_HELP' => '' .
        'Gibt an, welcher Wert als OXTRANSID in der Tabelle OXORDER gespeichert werden soll:<br />' .
        'ORDERID: die vom Modul erstellte ID (z.b.: mo_ogone_123456)<br />' .
        'PAYID: die von Ogone erstellte ID',
    'MO_OGONE__USE_IFRAME_FOR_HIDDEN_AUTH' => 'via iFrame',
    'MO_OGONE__USE_IFRAME_FOR_HIDDEN_AUTH_HELP' => '' .
        'Hat keinen Effekt, wenn Hidden Authorization deaktiviert ist<br />' .
        'Wahr: Zur Hidden Authorization wird ein iFrame des PSP verwendet (PCI konform)</br>' .
        'Falsch: Die Daten werden in einem Formular der Shopseite eingetragen, aber direkt an den PSP gesendet (nicht PCI konform, PCI-Zertifikat notwendig)',
    'OGONE_SETUP_TITLE' => 'Setup',
    'MO_OGONE__CAPTURE_CREDITCARD_HELP' => 'Transaktionstyp',
    'MO_OGONE__USE_UTF8_HELP' => 'Daten UTF8-kodiert an übertragen. Wenn Sie diese Option aktivieren, müssen Sie die Schnittstellen-URLs anpassen.',
    'MO_OGONE__API_USERID' => 'User-ID des Ogone API-Nutzers',
    'MO_OGONE__API_USERID_HELP' => '',
    'MO_OGONE__API_USERPASS' => 'Passwort des Ogone API-Nutzers',
    'MO_OGONE__API_USERPASS_HELP' => '',
    'MO_OGONE__SQL_INSTALL_ERROR_HEADER' => 'Konnte folgende Datenbankbefehle nicht ausführen, bitte lassen Sie Ihren Server-Admin die Befehle ausführen.',
    'MO_OGONE__ORDER_OVERVIEW_STATUS' => 'Ogone Status',
    'MO_OGONE__UNINSTALL_TPL_BLOCKS' => 'Uninstall (Azure) Tpl-Blocks',
    'MO_OGONE__INSTALL_TPL_BLOCKS' => 'Install (Azure) Tpl-Blocks',
);

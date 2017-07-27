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
 * @copyright (C) Ingenico 2009, 2010
 * @version 1.3
 * $Id: mo_ingenico__lang.php 25 2013-05-24 15:26:26Z martin $
 */
$sLangName = 'Deutsch';

// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
    'charset' => 'UTF-8',

    //API config
    'SHOP_MODULE_GROUP_moIngenicoApiConfiguration' => 'API Konfiguration',
    'SHOP_MODULE_mo_ingenico__isLiveMode' => 'Live Modus',
    'HELP_SHOP_MODULE_mo_ingenico__isLiveMode' => 'Zum Testen sollte die Option deaktiviert sein. '
        . 'Für den Produktivbetrieb aktivieren Sie die Option.',
    'SHOP_MODULE_mo_ingenico__logLevel' => 'Log Level',
    'HELP_SHOP_MODULE_mo_ingenico__logLevel' => 'Konfigurieren Sie hier in welchem Umfang Logeinträge geschrieben werden',
    'SHOP_MODULE_mo_ingenico__logLevel_DEBUG' => 'Debug',
    'SHOP_MODULE_mo_ingenico__logLevel_INFO' => 'Info',
    'SHOP_MODULE_mo_ingenico__logLevel_ERROR' => 'Error',
    'SHOP_MODULE_mo_ingenico__capture_creditcard' => 'Kreditkartentransaktionen reservieren',
    'HELP_SHOP_MODULE_mo_ingenico__capture_creditcard' => 'Ist die Option deaktiviert, werden die Beträge sofort eingezogen.',
    'SHOP_MODULE_mo_ingenico__use_utf8' => 'UTF-8 encode',
    'HELP_SHOP_MODULE_mo_ingenico__use_utf8' => 'Daten UTF8-kodiert an übertragen. Die Einstellung muss mit der Konfiguration im Ingenico Backend übereinstimmen. Konfiguration -> Technische Informationen -> Globale Sicherheitsparameter',
    'SHOP_MODULE_mo_ingenico__set_oxpaid' => 'Bezahldatum setzen',
    'HELP_SHOP_MODULE_mo_ingenico__set_oxpaid' => 'Setzt das Bezahldatum, wenn vom PSP ein OK-Status zurück kommt.<br />' .
        'ACHTUNG: Dieser Status bedeutet nicht, dass das Geld bei Ihrem Shop eingegangen ist.',
    'SHOP_MODULE_mo_ingenico__use_hidden_auth' => 'Hidden Authorization',
    'HELP_SHOP_MODULE_mo_ingenico__use_hidden_auth' => 'Bei der Verwendung von Hidden Authorization verlässt der Nutzer die Shopseite bei eintragen seiner Zahlungsinformationen nicht',
    'SHOP_MODULE_mo_ingenico__use_iframe' => 'via iFrame',
    'HELP_SHOP_MODULE_mo_ingenico__use_iframe' => '' .
        'Hat keinen Effekt, wenn Hidden Authorization deaktiviert ist<br />' .
        'Wahr: Zur Hidden Authorization wird ein iFrame des PSP verwendet (PCI konform)</br>' .
        'Falsch: Die Daten werden in einem Formular der Shopseite eingetragen, aber direkt an den PSP gesendet (nicht PCI konform, PCI-Zertifikat notwendig)',
    'SHOP_MODULE_mo_ingenico__use_alias_manager' => 'Alias Manager verwenden',
    'HELP_SHOP_MODULE_mo_ingenico__use_alias_manager' => '' .
        'Ermöglicht dem Nutzer Kreditkarten bei der nächsten Bestellung erneut zu verwenden',
    'SHOP_MODULE_mo_ingenico__transid_param' => 'In Order zu speichernde TransID',
    'HELP_SHOP_MODULE_mo_ingenico__transid_param' => '' .
        'Gibt an, welcher Wert als OXTRANSID in der Tabelle OXORDER gespeichert werden soll:<br />' .
        'ORDERID: die vom Modul erstellte ID (z.b.: mo_ingenico_123456)<br />' .
        'PAYID: die von Ingenico erstellte ID',
    'SHOP_MODULE_mo_ingenico__transid_param_ORDERID' => 'ORDERID',
    'SHOP_MODULE_mo_ingenico__transid_param_PAYID' => 'PAYID',
    'SHOP_MODULE_ingenico_sPSPID' => 'Ihre PSPID',
    'HELP_SHOP_MODULE_ingenico_sPSPID' => 'Tragen Sie hier Ihre (Test)-PSPID ein, die Sie vom Payment Service Provider erhalten haben.',
    'SHOP_MODULE_mo_ingenico__api_userid' => 'User-ID des Ingenico API-Nutzers',
    'HELP_SHOP_MODULE_mo_ingenico__api_userid' => 'Die API Nuzer können im Ingenico Backend verwaltet werden. Konfiguration -> Benutzerverwaltung.',
    'SHOP_MODULE_mo_ingenico__api_userpass' => 'Passwort des Ingenico API-Nutzers',
    'HELP_SHOP_MODULE_mo_ingenico__api_userpass' => 'Die Passwörter für API Nuzer können im Ingenico Backend verwaltet werden. Konfiguration -> Benutzerverwaltung.',
    'SHOP_MODULE_ingenico_sHashingAlgorithm' => 'SHA Hashing',
    'HELP_SHOP_MODULE_ingenico_sHashingAlgorithm' => 'Mit welchem Algorithmus wird der SHA Wert berechnet. Die Einstellung muss mit der Konfiguration im Ingenico Backend übereinstimmen. Konfiguration -> Technische Informationen -> Globale Sicherheitsparameter',
    'SHOP_MODULE_ingenico_sHashingAlgorithm_SHA-1' => 'SHA-1',
    'SHOP_MODULE_ingenico_sHashingAlgorithm_SHA-256' => 'SHA-256',
    'SHOP_MODULE_ingenico_sHashingAlgorithm_SHA-512' => 'SHA-512',
    'SHOP_MODULE_ingenico_sSecureKeyIn' => 'SHA-In Signatur',
    'HELP_SHOP_MODULE_ingenico_sSecureKeyIn' => 'Schlüssel für die Berechnung des SHA Wertes für ausgehende Anfragen. Die Einstellung muss mit der Konfiguration im Ingenico Backend übereinstimmen. Konfiguration -> Technische Informationen -> Daten- und Ursprungsüberprüfung',
    'SHOP_MODULE_ingenico_sSecureKeyOut' => 'SHA-Out Signatur',
    'HELP_SHOP_MODULE_ingenico_sSecureKeyOut' => 'Schlüssel für die Berechnung des SHA Wertes für eingehende Anfragen. Die Einstellung muss mit der Konfiguration im Ingenico Backend übereinstimmen. Konfiguration -> Technische Informationen -> Transaktions-Feedback',
    'SHOP_MODULE_mo_ingenico__timeout' => 'Timout für die API Kommunikation',
    'HELP_SHOP_MODULE_mo_ingenico__timeout' => 'Zeitüberschreitung für die Transaktion in Sekunden. WICHTIG: Der hier angegebene Wert sollte nicht größer als der Zeitüberschreitungswert in Ihrem System sein!',
    'SHOP_MODULE_mo_ingenico__debug_mode' => 'Debug Modus',
    'HELP_SHOP_MODULE_mo_ingenico__debug_mode' => 'Wenn aktiviert, werden zusätzliche Informationen im Frontend ausgegeben. Im Produktivbetrieb unbedingt deaktivieren',

    //payment page layout config
    'SHOP_MODULE_GROUP_moIngenicoLayoutPaymentPage' => 'Layout der Zahlungsseite',
    'SHOP_MODULE_ingenico_sTemplate' => 'Dynamische Vorlage verwenden',
    'HELP_SHOP_MODULE_ingenico_sTemplate' => 'Ist die Option deaktiviert, wird eine statische Vorlage verwendet.',
    'SHOP_MODULE_ingenico_sTplPMListStyle' => 'Layout der Zahlungsmethoden',
    'HELP_SHOP_MODULE_ingenico_sTplPMListStyle' => 'Die Liste der Zahlungsmethoden auf der Zahlungsseite gestalten',
    'SHOP_MODULE_ingenico_sTplPMListStyle_0' => 'Horizontal gruppierte Logos mit dem Gruppennamen links davon',
    'SHOP_MODULE_ingenico_sTplPMListStyle_1' => 'Horizontal gruppierte Logos ohne Gruppennamen',
    'SHOP_MODULE_ingenico_sTplPMListStyle_2' => 'Vertikale Liste von Logos mit Name',
    'SHOP_MODULE_ingenico_blBackButton' => 'Zurück Schaltfläche',
    'HELP_SHOP_MODULE_ingenico_blBackButton' => 'Soll ein "Zurück" Button eingeblendet werden?',
    'SHOP_MODULE_ingenico_blTplTitle' => 'Titel und Kopfzeile der Zahlungseite',
    'HELP_SHOP_MODULE_ingenico_blTplTitle' => 'Ist diese Option aktiv, wird der Inhalt des CMS Snippets "mo_ingenico_tplTitle" als Titel der Zahlungsseite verwendet.',
    'SHOP_MODULE_ingenico_sTplBGColor' => 'Hintergrundfarbe',
    'HELP_SHOP_MODULE_ingenico_sTplBGColor' => 'Voreingestellter Wert: white',
    'SHOP_MODULE_ingenico_sTplFontColor' => 'Textfarbe',
    'HELP_SHOP_MODULE_ingenico_sTplFontColor' => 'Voreingestellter Wert: black',
    'SHOP_MODULE_ingenico_sTplTableBGColor' => 'Farbe des Tabellenhintergrundes',
    'HELP_SHOP_MODULE_ingenico_sTplTableBGColor' => 'Voreingestellter Wert: white',
    'SHOP_MODULE_ingenico_sTplTbFontColor' => 'Farbe des Tabellentextes',
    'HELP_SHOP_MODULE_ingenico_sTplTbFontColor' => 'Voreingestellter Wert: black',
    'SHOP_MODULE_ingenico_sTplBtnBGColor' => 'Hintergrundfarbe von Schaltflächen',
    'HELP_SHOP_MODULE_ingenico_sTplBtnBGColor' => 'Voreingestellter Wert: -',
    'SHOP_MODULE_ingenico_sTplBtnFontColor' => 'Textfarbe von Schaltflächen',
    'HELP_SHOP_MODULE_ingenico_sTplBtnFontColor' => 'Voreingestellter Wert: black',
    'SHOP_MODULE_ingenico_sTplFontFamily' => 'Schriftart',
    'HELP_SHOP_MODULE_ingenico_sTplFontFamily' => 'Voreingestellter Wert: Verdana',
    'SHOP_MODULE_ingenico_sTplLogo' => 'Ihr Logo',
    'HELP_SHOP_MODULE_ingenico_sTplLogo' => 'URL/Dateiname des Logos, welches oben auf der Zahlungsseite neben dem Titel erscheinen soll. Die URL muss absolut sein. Das Logo muss auf einem sicheren Server gespeichert sein (https://www.ihrShop.de). Wenn Sie über keine sichere Umgebung zur Speicherung des Bildes verfügen, können Sie eine JPG- oder GIF-Datei (und Ihre PSPID) an support@ingenico.com senden. Dies ist nur für reguläre Produktiv-Konten möglich, weil kostenpflichtig. Bevor Sie uns das Logo schicken, aktivieren Sie bitte die Option "Logo Hosting" in Ihrem Produktiv-Konto unter Konfiguration > Konto >Ihre Optionen. Wenn das Logo auf unserem Server gespeichert ist, müssen Sie nur den Dateinamen angeben, nicht die komplette URL.',

    //interface
    'SHOP_MODULE_GROUP_moIngenicoInterface' => 'Statusmeldungen',
    'SHOP_MODULE_mo_ingenico__useGroupBy' => 'Identische Einträge nur einmal anzeigen',
    'HELP_SHOP_MODULE_mo_ingenico__useGroupBy' => 'Wenn deferred Feedback aktiviert ist, kann es sein dass zu einer' .
        ' Transaktion 2 mal ein identischer Eintrag gespeichert wird. Allerdings sind die Quellen für deas Feedback '
        . 'verschieden (Benutzer und deferred Feedback). Um die Übersicht zu verbessern,'
        . ' können die doppelten Einträge ausgeblendet werden.',

//aftersales
    'MO_INGENICO__REFUND_INCLUDE_SHIPMENT'  => 'Versandkosten auch Gutschreiben',
    'MO_INGENICO__REFUND_INCLUDE_GIFTCARD'  => 'Grußkarte auch Gutschreiben',
    'MO_INGENICO__CAPTURE_INCLUDE_SHIPMENT' => 'Versandkosten auch Einziehen',
    'MO_INGENICO__CAPTURE_INCLUDE_GIFTCARD' => 'Grußkarte auch Einziehen',
    'mo_ingenico__aftersales_capture' => 'Ingenico Zahlung einziehen',
    'mo_ingenico__aftersales_refund'  => 'Ingenico Zahlung gutschreiben',
    'MO_INGENICO__REFUND_EXPLANATION' => 'Hier haben Sie die Möglichkeit schon eingezogene Beträge wider gutzuschreiben.' .
        ' Bitte bedenken Sie, dass nur Beträge gutgeschrieben werden können, die auch schon eingezogen wurden' .
        ' und nicht schon früher gutgeschrieben wurden. Der Betrag, der insgesamt über alle Anfragen einer Bestellung' .
        ' gutgeschrieben wird, darf den Gesamtbetrag der Bestellung nicht überschreiten.',
    'MO_INGENICO__CAPTURE_EXPLANATION' => 'Hier haben Sie die Möglichkeit Beträge einzuziehen, die bisher nur reserviert wurden.' .
        ' Bitte bedenken Sie, dass nur Beträge eingezogen werden können, die reserviert wurden' .
        ' und nicht schon früher eingezogen wurden. Der Betrag, der insgesamt über alle Anfragen einer Bestellung' .
        ' eingezogen wird, darf den Gesamtbetrag der Bestellung nicht überschreiten.',


// Admin Menu
    'mo_ingenico' => 'Ingenico',
    'mo_ingenico__setup' => 'Einstellungen',
    'mo_ingenico__interface' => 'Statusmeldungen',
    'mo_ingenico__logfile' => 'Log',
// Admin -> Ingenico -> Statusmeldungen
    'INGENICO_DATE' => 'Datum',
    'INGENICO_ORDERID' => 'Best.Nr.',
    'INGENICO_TRANSID' => 'Trans.Nr.',
    'INGENICO_CUSTOMER_NAME' => 'Kunde',
    'INGENICO_CN' => 'Halter',
    'INGENICO_AMOUNT' => 'Betrag',
    'INGENICO_ACCEPTANCE' => 'Genehmigung',
    'INGENICO_STATUS' => 'Status',
    'INGENICO_CARDNO' => 'Nummer',
    'INGENICO_BRAND' => 'Brand',
    'INGENICO_ED' => 'Gültigk.',
    'INGENICO_PAYID' => 'Pay ID',
    'INGENICO_NCERROR' => 'Fehler',
    'INGENICO_TRXDATE' => 'Trans.Zeit',
// Admin -> Ingenico -> Log
    'MO_INGENICO_MESSAGE' => 'Nachricht',
    'MO_INGENICO_CONTEXT' => 'Parameter',
    'MO_INGENICO_EXTRA' => 'Zus. Infos',
// Admin -> Ingenico -> Einstellungen
    'MO_INGENICO_NOTHINGINTHELOGS' => 'Aktuell keine Logeinträge vorhanden',
    'MO_INGENICO__ISLIVEMODE' => 'Live-Modus',
    'MO_INGENICO_LOGLEVEL' => 'Log-Level',
    'MO_INGENICO_LOGLEVEL_DEBUG' => 'Debug',
    'MO_INGENICO_LOGLEVEL_INFO' => 'Info',
    'MO_INGENICO_LOGLEVEL_ERROR' => 'Error',
    'HELP_INGENICO_PSPID' => 'Tragen Sie hier Ihre (Test)-PSPID ein, die Sie vom Payment Service Provider erhalten haben.',
    'START_SETUP' => 'Installation beginnen',
    'UPDATE_SETUP' => 'Aktualisieren',
    'INGENICO_PAYMENT_METHODS' => 'Bitte übertragen Sie hier Ihre<br />bei Ingenico freigeschalteten Zahlungsarten:',
    'INGENICO_PAYMENT_METHODS_ALL_PAYMENT_METHODS' => 'Alle Zahlungsarten',
// Zahlarten
    'MO_INGENICO__PAYMENT_METHODS_CREDIT_CARD' => 'Kreditkarte (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_MAESTRO' => 'Maestro (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_POST_CARD' => 'PostFinance Card (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_GIROPAY' => 'giropay (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_SOFORTUEBERWEISUNG' => 'Sofortüberweisung.de (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_IDEAL' => 'iDEAL (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_POST_EFINANCE' => 'PostFinance E-Finance (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_EPS' => 'eps Österreich (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_DEBIT_DE' => 'Lastschrift DE (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_DEBIT_NL' => 'Lastschrift NL (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_PAYPAL' => 'PayPal (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_MPASS' => 'mPass (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_OPEN_INVOICE_DE' => 'BillPay (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_MISTER_CASH' => 'Mister Cash (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_VVV' => 'VVV Gift Card (Ingenico)',
    'MO_INGENICO__PAYMENT_METHODS_DANKORT' => 'Dankort (Ingenico)',
    'INGENICO_PAYMENT_METHODS_AMEX' => 'American Express',
    'INGENICO_PAYMENT_METHODS_VISA' => 'VISA',
    'INGENICO_PAYMENT_METHODS_MASTERCARD' => 'MasterCard',
    'INGENICO_PAYMENT_METHODS_JCB' => 'JCB',
    'INGENICO_PAYMENT_METHODS_BCMC' => 'Bancontact/Mister Cash',
    'INGENICO_PSPID' => 'Ihre PSPID:',
    'INGENICO_USERID' => 'Ihre USERID:',
    'INGENICO_URL' => 'URL der Bezahlplattform:',
    'PAYMENT_MAIN_REQUEST' => 'Aufruf der Bezahlplattform:',
    'PAYMENT_MAIN_REQUEST_IFRAME' => 'iFrame',
    'PAYMENT_MAIN_REQUEST_REDIRECT' => 'Weiterleitung',
    'HELP_PAYMENT_MAIN_REQUEST' => 'Aufruf zur Bezahlseite',
    'INGENICO_SECURE_KEY_IN' => 'SHA-In Signatur:',
    'INGENICO_SECURE_KEY_OUT' => 'SHA-Out Signatur:',
    'INGENICO_HASHING' => 'SHA Hashing:',
    'INGENICO_HASHING_ALGORITHM' => 'Hash-Algorithmus:',
    'INGENICO_HASHING_ALGORITHM_SHA_1' => 'SHA-1',
    'INGENICO_HASHING_ALGORITHM_SHA_256' => 'SHA-256',
    'INGENICO_HASHING_ALGORITHM_SHA_512' => 'SHA-512',
    'INGENICO_HASHING_ENCODING_1' => 'AUTO',
    'INGENICO_HASHING_ENCODING_2' => 'ISO-8859-1',
    'INGENICO_HASHING_ENCODING_3' => 'UTF-8',
    'INGENICO_OPERATION' => 'Operation:',
    'INGENICO_OPERATION_RES' => 'Autorisierung',
    'INGENICO_OPERATION_SAL' => 'Direktbuchung',
    'INGENICO_MODULE_DESCRIPTION' => 'Weitere Informationen zu den Zahlungsmodulen entnehmen Sie bitte der Dokumentation und dem F.A.Q., die Sie in dem Support Bereich Ihres Ingenico-Konto finden.',
    'INGENICO_TEST_ACCOUNT' => 'Testkonto',
    'INGENICO_PROD_ACCOUNT' => 'Produktivkonto',
    'INGENICO_TEMPLATE' => 'Layout der Zahlungsseite:',
    'INGENICO_TEMPLATE_TRUE' => 'Dynamische Vorlage',
    'INGENICO_TEMPLATE_FALSE' => 'Statische Vorlage',
    'INGENICO_PMLISTSTYLE_TITLE' => 'Layout der Zahlungsmethoden:',
    'INGENICO_PMLISTSTYLE_TITLE_DESCRIPTION' => 'Die Liste der Zahlungsmethoden auf der Zahlungsseite gestalten',
    'INGENICO_PMLISTSTYLE_0' => 'Horizontal gruppierte Logos mit dem Gruppennamen links davon',
    'INGENICO_PMLISTSTYLE_1' => 'Horizontal gruppierte Logos ohne Gruppennamen',
    'INGENICO_PMLISTSTYLE_2' => 'Vertikale Liste von Logos mit Name',
    'INGENICO_BACK_TITLE' => 'Zurück-Schaltfläche:',
    'INGENICO_TEMPLATE_TITLE' => 'Titel und Kopfzeile der Zahlungseite:',
    'INGENICO_TEMPLATE_TITLE_DESCRIPTION' => 'Voreingestellter Wert:',
    'INGENICO_TEMPLATE_BGCOLOR' => 'Hintergrundfarbe:',
    'INGENICO_TEMPLATE_BGCOLOR_DESCRIPTION' => 'Voreingestellter Wert: white',
    'INGENICO_TEMPLATE_FONTCOLOR' => 'Textfarbe:',
    'INGENICO_TEMPLATE_FONTCOLOR_DESCRIPTION' => 'Voreingestellter Wert: black',
    'INGENICO_TEMPLATE_TABLE_BGCOLOR' => 'Farbe des Tabellenhintergrundes:',
    'INGENICO_TEMPLATE_TABLE_BGCOLOR_DESCRIPTION' => 'Voreingestellter Wert: white',
    'INGENICO_TEMPLATE_TABLE_FONTCOLOR' => 'Farbe des Tabellentextes:',
    'INGENICO_TEMPLATE_TABLE_FONTCOLOR_DESCRIPTION' => 'Voreingestellter Wert: black',
    'INGENICO_TEMPLATE_BUTTON_BGCOLOR' => 'Hintergrundfarbe von Schaltflächen:',
    'INGENICO_TEMPLATE_BUTTON_BGCOLOR_DESCRIPTION' => 'Voreingestellter Wert:',
    'INGENICO_TEMPLATE_BUTTON_FONTCOLOR' => 'Textfarbe von Schaltflächen:',
    'INGENICO_TEMPLATE_BUTTON_FONTCOLOR_DESCRIPTION' => 'Voreingestellter Wert: black',
    'INGENICO_TEMPLATE_FONTFAMILY' => 'Schriftart:',
    'INGENICO_TEMPLATE_FONTFAMILY_DESCRIPTION' => 'Voreingestellter Wert: Verdana',
    'INGENICO_TEMPLATE_LOGO' => 'Ihr Logo:',
    'INGENICO_TEMPLATE_LOGO_DESCRIPTION' => 'URL/Dateiname des Logos, das oben auf der Zahlungsseite neben dem Titel erscheinen soll. Die URL muss absolut sein. Das Logo muss auf einem sicheren Server gespeichert sein (https://www.ihrShop.de). Wenn Sie über keine sichere Umgebung zur Speicherung des Bildes verfügen, können Sie eine JPG- oder GIF-Datei (und Ihre PSPID) an support@ingenico.com senden. Dies ist nur für reguläre Produktiv-Konten möglich, weil kostenpflichtig. Bevor Sie uns das Logo schicken, aktivieren Sie bitte die Option "Logo Hosting" in Ihrem Produktiv-Konto unter Konfiguration > Konto >Ihre Optionen. Wenn das Logo auf unserem Server gespeichert ist, müssen Sie nur den Dateinamen angeben, nicht die komplette URL.',
    'INGENICO_ALIAS' => 'Alias Manager:',
    'INGENICO_ALIAS_MANAGER' => 'Alias Manager aktivieren',
    'INGENICO_ALIAS_OPERATION' => 'Vergabe des Alias',
    'INGENICO_ALIAS_OPERATION_BYMERCHANT' => 'konfigurieren',
    'INGENICO_ALIAS_OPERATION_BYINGENICO' => 'automatisch',
    'INGENICO_ALIAS_OPERATION_DESCRIPTION' => 'Konfigurieren Sie mit der nachfolgenden Einstellung wie der Alias-Name<br />' .
    'generiert wird. Oder lassen Sie den Alias-Namen automatisch durch Ingenico<br />' .
    'vergeben.',
    'INGENICO_ALIAS_FORMAT' => 'Alias-Format',
    'INGENICO_ALIAS_FORMAT_DESCRIPTION' => 'Formatierung des Aliasnamen mittels folgender Parameter:<br />' .
    '{shopname} = Name Ihres Shops<br />' .
    '{firstname} = Vorname des Kunden<br />' .
    '{lastname} = Nachname des Kunden<br />' .
    '{customersid} = Kundennumer<br />',
    'INGENICO_ALIAS_USAGE' => 'Alias-Beschreibung',
    'INGENICO_ALIAS_USAGE_DESCRIPTION' => 'Beschreibung die dem Kunden als Grund für die Alias-Registrierung angezeigt wird.',
    'MO_INGENICO__CAPTURE_CREDITCARD' => 'Kreditkartentransaktionen reservieren',
    'MO_INGENICO__SET_OXPAID' => 'Bezahldatum setzen',
    'MO_INGENICO__SET_OXPAID_HELP' => 'Setzt das Bezahldatum, wenn vom PSP ein OK-Status zurück kommt.<br />' .
        'ACHTUNG: Dieser Status bedeutet nicht, dass das Geld bei Ihrem Shop eingegangen ist.',
    'MO_INGENICO__USE_UTF8' => 'UTF-8 encode',
    'MO_INGENICO__USE_HIDDEN_AUTH' => 'Hidden Authorization',
    'MO_INGENICO__USE_HIDDEN_AUTH_HELP' => 'Bei der Verwendung von Hidden Authorization verlässt der Nutzer die Shopseite bei eintragen seiner Zahlungsinformationen nicht',
    'MO_INGENICO__OXTRANSID_PARAM' => 'In Order zu speichernde TransID',
    'MO_INGENICO__OXTRANSID_PARAM_HELP' => '' .
        'Gibt an, welcher Wert als OXTRANSID in der Tabelle OXORDER gespeichert werden soll:<br />' .
        'ORDERID: die vom Modul erstellte ID (z.b.: mo_ingenico_123456)<br />' .
        'PAYID: die von Ingenico erstellte ID',
    'MO_INGENICO__USE_IFRAME_FOR_HIDDEN_AUTH' => 'via iFrame',
    'MO_INGENICO__USE_IFRAME_FOR_HIDDEN_AUTH_HELP' => '' .
        'Hat keinen Effekt, wenn Hidden Authorization deaktiviert ist<br />' .
        'Wahr: Zur Hidden Authorization wird ein iFrame des PSP verwendet (PCI konform)</br>' .
        'Falsch: Die Daten werden in einem Formular der Shopseite eingetragen, aber direkt an den PSP gesendet (nicht PCI konform, PCI-Zertifikat notwendig)',
    'INGENICO_SETUP_TITLE' => 'Setup',
    'MO_INGENICO__CAPTURE_CREDITCARD_HELP' => 'Transaktionstyp',
    'MO_INGENICO__USE_UTF8_HELP' => 'Daten UTF8-kodiert an übertragen. Wenn Sie diese Option aktivieren, müssen Sie die Schnittstellen-URLs anpassen.',
    'MO_INGENICO__API_USERID' => 'User-ID des Ingenico API-Nutzers',
    'MO_INGENICO__API_USERID_HELP' => '',
    'MO_INGENICO__API_USERPASS' => 'Passwort des Ingenico API-Nutzers',
    'MO_INGENICO__API_USERPASS_HELP' => '',
    'MO_INGENICO__SQL_INSTALL_ERROR_HEADER' => 'Konnte folgende Datenbankbefehle nicht ausführen, bitte lassen Sie Ihren Server-Admin die Befehle ausführen.',
    'MO_INGENICO__ORDER_OVERVIEW_STATUS' => 'Ingenico Status',
    'MO_INGENICO__UNINSTALL_TPL_BLOCKS' => 'Uninstall (Azure) Tpl-Blocks',
    'MO_INGENICO__INSTALL_TPL_BLOCKS' => 'Install (Azure) Tpl-Blocks',
);

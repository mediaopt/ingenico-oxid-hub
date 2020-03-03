<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id' => 'mo_ingenico',
    'title' => array(
        'de' => 'Ingenico',
        'en' => 'Ingenico',
    ),
    'description' => array(
        'de' => 'Ingenico Zahlungen<br /><br />Die Dokumentation finden Sie <a style="text-decoration: underline;" '
            . 'target="_blank" href="https://projects.mediaopt.de/projects/ogone-puc/wiki/Dokumentation_DE">hier</a>'
            . '<p>Übersicht der Änderungen: <a href="https://projects.mediaopt.de/projects/ogone-puc/wiki/Changelog_DE" style="text-decoration: underline;"'
            . ' target="_blank">Changelog</a></p>',
        'en' => 'Ingenico Payment<br /><br />The documentation is available <a style="text-decoration: underline;" '
            . 'target="_blank" href="https://projects.mediaopt.de/projects/ogone-puc/wiki/Dokumentation_EN">here</a>'
            . '<p>Recent changes: <a href="https://projects.mediaopt.de/projects/ogone-puc/wiki/Changelog_EN" style="text-decoration: underline;"'
            . ' target="_blank">changelog</a></p>',
    ),
    'thumbnail' => 'logo.png',
    'version' => '5.0.5',
    'author' => '<a href="https://www.mediaopt.de" target="_blank">mediaopt</a>',
    'url' => 'https://www.mediaopt.de',
    'email' => 'support@mediaopt.de',
    'extend' => array(
        //components
        //controllers (admin)
        //controllers (frontend)
        'order' => 'mo/mo_ingenico/controllers/mo_ingenico__order',
        'payment' => 'mo/mo_ingenico/controllers/mo_ingenico__payment',
        //models
        'oxbasketitem' => 'mo/mo_ingenico/models/mo_ingenico__oxbasketitem',
        'oxorder' => 'mo/mo_ingenico/models/mo_ingenico__oxorder',
        'oxoutput' => 'mo/mo_ingenico/models/mo_ingenico__oxoutput',
        'oxpayment' => 'mo/mo_ingenico/models/mo_ingenico__oxpayment',
        'oxuser' => 'mo/mo_ingenico/models/mo_ingenico__oxuser',
        //core
    ),
    'files' => array(
        //controllers (admin)
        'mo_ingenico__interface' => 'mo/mo_ingenico/controllers/admin/mo_ingenico__interface.php',
        'mo_ingenico__logfile' => 'mo/mo_ingenico/controllers/admin/mo_ingenico__logfile.php',
        'mo_ingenico__setup' => 'mo/mo_ingenico/controllers/admin/mo_ingenico__setup.php',
        'mo_ingenico__capture' => 'mo/mo_ingenico/controllers/admin/aftersales/mo_ingenico__capture.php',
        'mo_ingenico__refund' => 'mo/mo_ingenico/controllers/admin/aftersales/mo_ingenico__refund.php',
        //controllers (frontend)
        'mo_ingenico__deferred_feedback' => 'mo/mo_ingenico/controllers/mo_ingenico__deferred_feedback.php',
        'mo_ingenico__order_error' => 'mo/mo_ingenico/controllers/mo_ingenico__order_error.php',
        'mo_ingenico__payment_form' => 'mo/mo_ingenico/controllers/mo_ingenico__payment_form.php',
        'mo_ingenico__template' => 'mo/mo_ingenico/controllers/mo_ingenico__template.php',
        'mo_ingenico__manage_aliases' => 'mo/mo_ingenico/controllers/mo_ingenico__manage_aliases.php',
        //models
        'mo_ingenico__order_number_reservation' => 'mo/mo_ingenico/models/mo_ingenico__order_number_reservation.php',
        'mo_ingenico__payment_log' => 'mo/mo_ingenico/models/mo_ingenico__payment_log.php',
        'mo_ingenico__alias' => 'mo/mo_ingenico/models/mo_ingenico__alias.php',
        //adapter
        'mo_ingenico__abstract_factory' => 'mo/mo_ingenico/classes/adapter/mo_ingenico__abstract_factory.php',
        'mo_ingenico__sha_settings' => 'mo/mo_ingenico/classes/adapter/mo_ingenico__sha_settings.php',
        'mo_ingenico__request_param_builder' => 'mo/mo_ingenico/classes/adapter/mo_ingenico__request_param_builder.php',
        'mo_ingenico__alias_param_builder' => 'mo/mo_ingenico/classes/adapter/mo_ingenico__alias_param_builder.php',
        'mo_ingenico__order_redirect_param_builder' => 'mo/mo_ingenico/classes/adapter/mo_ingenico__order_redirect_param_builder.php',
        'mo_ingenico__order_direct_param_builder' => 'mo/mo_ingenico/classes/adapter/mo_ingenico__order_direct_param_builder.php',
        'mo_ingenico__hosted_tokenization_param_builder' => 'mo/mo_ingenico/classes/adapter/mo_ingenico__hosted_tokenization_param_builder.php',
        'mo_ingenico__aftersale_param_builder' => 'mo/mo_ingenico/classes/adapter/mo_ingenico__aftersale_param_builder.php',
        //core
        'mo_ingenico__config' => 'mo/mo_ingenico/classes/mo_ingenico__config.php',
        'mo_ingenico__main' => 'mo/mo_ingenico/classes/mo_ingenico__main.php',
        'mo_ingenico__helper' => 'mo/mo_ingenico/classes/mo_ingenico__helper.php',
        'mo_ingenico__transaction_logger' => 'mo/mo_ingenico/classes/mo_ingenico__transaction_logger.php',
        'mo_ingenico__monolog_processor' => 'mo/mo_ingenico/classes/mo_ingenico__monolog_processor.php',
        'mo_ingenico__log_parser' => 'mo/mo_ingenico/classes/mo_ingenico__log_parser.php',
        'mo_ingenico__browserinfo' => 'mo/mo_ingenico/classes/mo_ingenico__browserinfo.php',
        //events
        'mo_ingenico__events' => 'mo/mo_ingenico/setup/mo_ingenico__events.php',
        'mo_ingenico__sql' => 'mo/mo_ingenico/setup/mo_ingenico__sql.php',
    ),
    'blocks' => array(
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'select_payment',
            'file' => 'views/blocks/page/checkout/payment/select_payment.tpl'
        ),
        array(
            'template' => 'order_overview.tpl',
            'block' => 'admin_order_overview_status',
            'file' => 'views/blocks/admin/admin_order_overview_status.tpl'
        ),
        array(
            'template' => 'page/account/dashboard.tpl',
            'block'    => 'account_dashboard_col2',
            'file'     => 'views/blocks/page/account/account_dashboard_col2.tpl'
        ),
        array(
            'template'  => 'page/account/inc/account_menu.tpl',
            'block'     => 'account_menu',
            'file'      => 'views/blocks/page/account/inc/account_menu.tpl'
        ),

    ),
    'templates' => array(
        //admin templates
        'mo_ingenico__interface.tpl' => 'mo/mo_ingenico/views/admin/tpl/mo_ingenico__interface.tpl',
        'mo_ingenico__logfile.tpl' => 'mo/mo_ingenico/views/admin/tpl/mo_ingenico__logfile.tpl',
        'mo_ingenico__setup.tpl' => 'mo/mo_ingenico/views/admin/tpl/mo_ingenico__setup.tpl',
        'mo_ingenico__aftersales.tpl' => 'mo/mo_ingenico/views/admin/tpl/mo_ingenico__aftersales.tpl',
        'mo_ingenico__capture.tpl' => 'mo/mo_ingenico/views/admin/tpl/mo_ingenico__capture.tpl',
        'mo_ingenico__refund.tpl' => 'mo/mo_ingenico/views/admin/tpl/mo_ingenico__refund.tpl',

        //azure templates
        'mo_ingenico__payment_creditcard.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/checkout/inc/mo_ingenico__payment_creditcard.tpl',
        'mo_ingenico__payment_creditcard_form_fields.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/checkout/inc/mo_ingenico__payment_creditcard_form_fields.tpl',
        'mo_ingenico__payment_invoice.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/checkout/inc/mo_ingenico__payment_invoice.tpl',
        'mo_ingenico__payment_one_page.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/checkout/mo_ingenico__payment_one_page.tpl',
        'mo_ingenico__account_dashboard_field.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/account/mo_ingenico__account_dashboard_field.tpl',
        'mo_ingenico__manage_aliases.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/account/mo_ingenico__manage_aliases.tpl',
        'payment_form.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/mo_ingenico/payment_form.tpl',

        //flow templates
        'mo_ingenico__flow_payment_creditcard.tpl' => 'mo/mo_ingenico/views/flow/tpl/page/checkout/inc/mo_ingenico__flow_payment_creditcard.tpl',
        'mo_ingenico__flow_payment_creditcard_form_fields.tpl' => 'mo/mo_ingenico/views/flow/tpl/page/checkout/inc/mo_ingenico__flow_payment_creditcard_form_fields.tpl',
        'mo_ingenico__flow_payment_invoice.tpl' => 'mo/mo_ingenico/views/flow/tpl/page/checkout/inc/mo_ingenico__flow_payment_invoice.tpl',
        'mo_ingenico__flow_payment_one_page.tpl' => 'mo/mo_ingenico/views/flow/tpl/page/checkout/mo_ingenico__flow_payment_one_page.tpl',
        'mo_ingenico__flow_account_dashboard_field.tpl' => 'mo/mo_ingenico/views/flow/tpl/page/account/mo_ingenico__flow_account_dashboard_field.tpl',
        'mo_ingenico__flow_manage_aliases.tpl' => 'mo/mo_ingenico/views/flow/tpl/page/account/mo_ingenico__flow_manage_aliases.tpl',
        'flow_payment_form.tpl' => 'mo/mo_ingenico/views/flow/tpl/page/mo_ingenico/flow_payment_form.tpl',

        //common templates
        'dynamic.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/mo_ingenico/dynamic.tpl',
        'dynamic_mobile.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/mo_ingenico/dynamic_mobile.tpl',
        'order_error.tpl' => 'mo/mo_ingenico/views/azure/tpl/page/mo_ingenico/order_error.tpl',

    ),
    'settings'    => array(
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__isLiveMode',
            'type'  => 'bool',
            'value' => '0'
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__logLevel',
            'type' => 'select',
            'constrains' => 'DEBUG|INFO|ERROR',
            'value' => 'ERROR',
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__capture_creditcard',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__use_utf8',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__set_oxpaid',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__use_hidden_auth',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__use_iframe',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__use_alias_manager',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__transid_param',
            'type' => 'select',
            'constrains' => 'ORDERID|PAYID',
            'value' => 'PAYID',
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'ingenico_sPSPID',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__api_userid',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__api_userpass',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'ingenico_sHashingAlgorithm',
            'type' => 'select',
            'constrains' => 'SHA-1|SHA-256|SHA-512',
            'value' => 'SHA-512',
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'ingenico_sSecureKeyIn',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'ingenico_sSecureKeyOut',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__timeout',
            'type'  => 'str',
            'value' => '15'
        ),
        array(
            'group' => 'moIngenicoApiConfiguration',
            'name'  => 'mo_ingenico__debug_mode',
            'type'  => 'bool',
            'value' => '0'
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTemplate',
            'type' => 'bool',
            'value' => '1',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplPMListStyle',
            'type' => 'select',
            'constrains' => '0|1|2',
            'value' => '0',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_blBackButton',
            'type' => 'bool',
            'value' => '1',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_blTplTitle',
            'type' => 'bool',
            'value' => '0',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplBGColor',
            'type' => 'str',
            'value' => 'white',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplFontColor',
            'type' => 'str',
            'value' => 'black',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplTableBGColor',
            'type' => 'str',
            'value' => 'white',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplTbFontColor',
            'type' => 'str',
            'value' => 'black',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplBtnBGColor',
            'type' => 'str',
            'value' => '',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplBtnFontColor',
            'type' => 'str',
            'value' => 'black',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplFontFamily',
            'type' => 'str',
            'value' => 'verdana',
        ),
        array(
            'group' => 'moIngenicoLayoutPaymentPage',
            'name'  => 'ingenico_sTplLogo',
            'type' => 'str',
            'value' => '',
        ),
        array(
            'group' => 'moIngenicoInterface',
            'name'  => 'mo_ingenico__useGroupBy',
            'type' => 'bool',
            'value' => '1',
        ),
    ),
    'events' => array(
        'onActivate' => 'mo_ingenico__events::onActivate',
        'onDeactivate' => 'mo_ingenico__events::onDeactivate',
    ),
);

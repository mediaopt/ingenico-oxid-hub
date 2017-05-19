<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id' => 'mo_ogone',
    'title' => array(
        'de' => 'Ogone',
        'en' => 'Ogone',
    ),
    'description' => array(
        'de' => 'Ogone Zahlungen<br /><br />Die Dokumentation finden Sie <a style="text-decoration: underline;" '
            . 'target="_blank" href="https://projects.mediaopt.de/projects/ogone-puc/wiki/">hier</a>'
            . '<p>Übersicht der Änderungen: <a href="https://projects.mediaopt.de/projects/ogone-puc/wiki/Changelog" style="text-decoration: underline;"'
            . ' target="_blank">Changelog</a></p>',
        'en' => 'Ogone Payment<br /><br />The documentation is available <a style="text-decoration: underline;" '
            . 'target="_blank" href="https://projects.mediaopt.de/projects/ogone-puc/wiki/">here</a>'
            . '<p>Recent changes: <a href="https://projects.mediaopt.de/projects/ogone-puc/wiki/Changelog" style="text-decoration: underline;"'
            . ' target="_blank">changelog</a></p>',
    ),
    'thumbnail' => 'logo.png',
    'version' => '4.0.2',
    'author' => '<a href="https://www.mediaopt.de" target="_blank">mediaopt</a>',
    'url' => 'https://www.mediaopt.de',
    'email' => 'support@mediaopt.de',
    'extend' => array(
        //components
        //controllers (admin)
        //controllers (frontend)
        'order' => 'mo/mo_ogone/controllers/mo_ogone__order',
        'payment' => 'mo/mo_ogone/controllers/mo_ogone__payment',
        //models
        'oxbasketitem' => 'mo/mo_ogone/models/mo_ogone__oxbasketitem',
        'oxorder' => 'mo/mo_ogone/models/mo_ogone__oxorder',
        'oxoutput' => 'mo/mo_ogone/models/mo_ogone__oxoutput',
        'oxpayment' => 'mo/mo_ogone/models/mo_ogone__oxpayment',
        'oxuser' => 'mo/mo_ogone/models/mo_ogone__oxuser',
        //core
    ),
    'files' => array(
        //controllers (admin)
        'mo_ogone__interface' => 'mo/mo_ogone/controllers/admin/mo_ogone__interface.php',
        'mo_ogone__logfile' => 'mo/mo_ogone/controllers/admin/mo_ogone__logfile.php',
        'mo_ogone__setup' => 'mo/mo_ogone/controllers/admin/mo_ogone__setup.php',
        'mo_ogone__capture' => 'mo/mo_ogone/controllers/admin/aftersales/mo_ogone__capture.php',
        'mo_ogone__refund' => 'mo/mo_ogone/controllers/admin/aftersales/mo_ogone__refund.php',
        //controllers (frontend)
        'mo_ogone__deferred_feedback' => 'mo/mo_ogone/controllers/mo_ogone__deferred_feedback.php',
        'mo_ogone__order_error' => 'mo/mo_ogone/controllers/mo_ogone__order_error.php',
        'mo_ogone__payment_form' => 'mo/mo_ogone/controllers/mo_ogone__payment_form.php',
        'mo_ogone__template' => 'mo/mo_ogone/controllers/mo_ogone__template.php',
        'mo_ogone__manage_aliases' => 'mo/mo_ogone/controllers/mo_ogone__manage_aliases.php',
        //models
        'mo_ogone__order_number_reservation' => 'mo/mo_ogone/models/mo_ogone__order_number_reservation.php',
        'mo_ogone__payment_log' => 'mo/mo_ogone/models/mo_ogone__payment_log.php',
        'mo_ogone__alias' => 'mo/mo_ogone/models/mo_ogone__alias.php',
        //adapter
        'mo_ogone__abstract_factory' => 'mo/mo_ogone/classes/adapter/mo_ogone__abstract_factory.php',
        'mo_ogone__sha_settings' => 'mo/mo_ogone/classes/adapter/mo_ogone__sha_settings.php',
        'mo_ogone__request_param_builder' => 'mo/mo_ogone/classes/adapter/mo_ogone__request_param_builder.php',
        'mo_ogone__alias_param_builder' => 'mo/mo_ogone/classes/adapter/mo_ogone__alias_param_builder.php',
        'mo_ogone__order_redirect_param_builder' => 'mo/mo_ogone/classes/adapter/mo_ogone__order_redirect_param_builder.php',
        'mo_ogone__order_direct_param_builder' => 'mo/mo_ogone/classes/adapter/mo_ogone__order_direct_param_builder.php',
        'mo_ogone__hosted_tokenization_param_builder' => 'mo/mo_ogone/classes/adapter/mo_ogone__hosted_tokenization_param_builder.php',
        'mo_ogone__aftersale_param_builder' => 'mo/mo_ogone/classes/adapter/mo_ogone__aftersale_param_builder.php',
        //core
        'mo_ogone__config' => 'mo/mo_ogone/classes/mo_ogone__config.php',
        'mo_ogone__main' => 'mo/mo_ogone/classes/mo_ogone__main.php',
        'mo_ogone__helper' => 'mo/mo_ogone/classes/mo_ogone__helper.php',
        'mo_ogone__transaction_logger' => 'mo/mo_ogone/classes/mo_ogone__transaction_logger.php',
        'mo_ogone__monolog_processor' => 'mo/mo_ogone/classes/mo_ogone__monolog_processor.php',
        'mo_ogone__log_parser' => 'mo/mo_ogone/classes/mo_ogone__log_parser.php',
        //events
        'mo_ogone__events' => 'mo/mo_ogone/setup/mo_ogone__events.php',
        'mo_ogone__sql' => 'mo/mo_ogone/setup/mo_ogone__sql.php',
    ),
    'blocks' => array(
        array(
            'template' => 'page/checkout/payment.tpl',
            'block' => 'select_payment',
            'file' => 'views/blocks/page/checkout/payment/select_payment.tpl'
        ),
        array(
            'template' => 'order_overview.tpl',
            'block' => 'admin_order_overview_export',
            'file' => 'views/blocks/admin/admin_order_overview_export.tpl'
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
        'mo_ogone__interface.tpl' => 'mo/mo_ogone/views/admin/tpl/mo_ogone__interface.tpl',
        'mo_ogone__logfile.tpl' => 'mo/mo_ogone/views/admin/tpl/mo_ogone__logfile.tpl',
        'mo_ogone__setup.tpl' => 'mo/mo_ogone/views/admin/tpl/mo_ogone__setup.tpl',
        'mo_ogone__aftersales.tpl' => 'mo/mo_ogone/views/admin/tpl/mo_ogone__aftersales.tpl',
        'mo_ogone__capture.tpl' => 'mo/mo_ogone/views/admin/tpl/mo_ogone__capture.tpl',
        'mo_ogone__refund.tpl' => 'mo/mo_ogone/views/admin/tpl/mo_ogone__refund.tpl',

        //azure templates
        'mo_ogone__payment_creditcard.tpl' => 'mo/mo_ogone/views/azure/tpl/page/checkout/inc/mo_ogone__payment_creditcard.tpl',
        'mo_ogone__payment_creditcard_form_fields.tpl' => 'mo/mo_ogone/views/azure/tpl/page/checkout/inc/mo_ogone__payment_creditcard_form_fields.tpl',
        'mo_ogone__payment_invoice.tpl' => 'mo/mo_ogone/views/azure/tpl/page/checkout/inc/mo_ogone__payment_invoice.tpl',
        'mo_ogone__payment_one_page.tpl' => 'mo/mo_ogone/views/azure/tpl/page/checkout/mo_ogone__payment_one_page.tpl',
        'mo_ogone__account_dashboard_field.tpl' => 'mo/mo_ogone/views/azure/tpl/page/account/mo_ogone__account_dashboard_field.tpl',
        'mo_ogone__manage_aliases.tpl' => 'mo/mo_ogone/views/azure/tpl/page/account/mo_ogone__manage_aliases.tpl',
        'payment_form.tpl' => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/payment_form.tpl',

        //flow templates
        'mo_ogone__flow_payment_creditcard.tpl' => 'mo/mo_ogone/views/flow/tpl/page/checkout/inc/mo_ogone__flow_payment_creditcard.tpl',
        'mo_ogone__flow_payment_creditcard_form_fields.tpl' => 'mo/mo_ogone/views/flow/tpl/page/checkout/inc/mo_ogone__flow_payment_creditcard_form_fields.tpl',
        'mo_ogone__flow_payment_invoice.tpl' => 'mo/mo_ogone/views/flow/tpl/page/checkout/inc/mo_ogone__flow_payment_invoice.tpl',
        'mo_ogone__flow_payment_one_page.tpl' => 'mo/mo_ogone/views/flow/tpl/page/checkout/mo_ogone__flow_payment_one_page.tpl',
        'mo_ogone__flow_account_dashboard_field.tpl' => 'mo/mo_ogone/views/flow/tpl/page/account/mo_ogone__flow_account_dashboard_field.tpl',
        'mo_ogone__flow_manage_aliases.tpl' => 'mo/mo_ogone/views/flow/tpl/page/account/mo_ogone__flow_manage_aliases.tpl',
        'flow_payment_form.tpl' => 'mo/mo_ogone/views/flow/tpl/page/mo_ogone/flow_payment_form.tpl',

        //common templates
        'dynamic.tpl' => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/dynamic.tpl',
        'dynamic_mobile.tpl' => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/dynamic_mobile.tpl',
        'order_error.tpl' => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/order_error.tpl',

    ),
    'settings'    => array(
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__isLiveMode',
            'type'  => 'bool',
            'value' => '0'
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__logLevel',
            'type' => 'select',
            'constrains' => 'DEBUG|INFO|ERROR',
            'value' => 'ERROR',
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__capture_creditcard',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__use_utf8',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__set_oxpaid',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__use_hidden_auth',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__use_iframe',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__use_alias_manager',
            'type'  => 'bool',
            'value' => '1'
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__transid_param',
            'type' => 'select',
            'constrains' => 'ORDERID|PAYID',
            'value' => 'PAYID',
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'ogone_sPSPID',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__api_userid',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__api_userpass',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'ogone_sHashingAlgorithm',
            'type' => 'select',
            'constrains' => 'SHA-1|SHA-256|SHA-512',
            'value' => 'SHA-512',
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'ogone_sSecureKeyIn',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'ogone_sSecureKeyOut',
            'type'  => 'str',
            'value' => ''
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__timeout',
            'type'  => 'str',
            'value' => '15'
        ),
        array(
            'group' => 'moOgoneApiConfiguration',
            'name'  => 'mo_ogone__debug_mode',
            'type'  => 'bool',
            'value' => '0'
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTemplate',
            'type' => 'bool',
            'value' => '1',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplPMListStyle',
            'type' => 'select',
            'constrains' => '0|1|2',
            'value' => '0',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_blBackButton',
            'type' => 'bool',
            'value' => '1',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_blTplTitle',
            'type' => 'bool',
            'value' => '0',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplBGColor',
            'type' => 'str',
            'value' => 'white',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplFontColor',
            'type' => 'str',
            'value' => 'black',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplTableBGColor',
            'type' => 'str',
            'value' => 'white',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplTbFontColor',
            'type' => 'str',
            'value' => 'black',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplBtnBGColor',
            'type' => 'str',
            'value' => '',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplBtnFontColor',
            'type' => 'str',
            'value' => 'black',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplFontFamily',
            'type' => 'str',
            'value' => 'verdana',
        ),
        array(
            'group' => 'moOgoneLayoutPaymentPage',
            'name'  => 'ogone_sTplLogo',
            'type' => 'str',
            'value' => '',
        ),
        array(
            'group' => 'moOgoneInterface',
            'name'  => 'mo_ogone__useGroupBy',
            'type' => 'bool',
            'value' => '1',
        ),
    ),
    'events' => array(
        'onActivate' => 'mo_ogone__events::onActivate',
        'onDeactivate' => 'mo_ogone__events::onDeactivate',
    ),
);

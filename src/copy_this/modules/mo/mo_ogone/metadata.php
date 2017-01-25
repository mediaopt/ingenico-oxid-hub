<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'mo_ogone',
    'title'       => 'Ogone',
    'description' => 'Ogone Payment ##revision##<br /><br />'
                   . 'Die Dokumentation <a target="_blank" href="https://projects.mediaopt.de/projects/ogone-puc/wiki/">finden Sie hier</a>',
    'thumbnail'   => 'logo.png',
    'version'     => '3.3.0',
    'author'      => '<a href="http://www.mediaopt.de" target="_blank">mediaopt.</a>',
    'url'         => 'http://www.mediaopt.de',
    'email'       => 'support@mediaopt.de',
    'extend'      => array(
        //components
        //controllers (admin)
        //controllers (frontend)
        'order'        => 'mo/mo_ogone/controllers/mo_ogone__order',
        'payment'      => 'mo/mo_ogone/controllers/mo_ogone__payment',
        //models
        'oxbasketitem' => 'mo/mo_ogone/models/mo_ogone__oxbasketitem',
        'oxorder'      => 'mo/mo_ogone/models/mo_ogone__oxorder',
        'oxoutput'     => 'mo/mo_ogone/models/mo_ogone__oxoutput',
        'oxpayment'    => 'mo/mo_ogone/models/mo_ogone__oxpayment',
        //core
    ),
    'files'       => array(
        //controllers (admin)
        'mo_ogone__interface'                => 'mo/mo_ogone/controllers/admin/mo_ogone__interface.php',
        'mo_ogone__logfile'                  => 'mo/mo_ogone/controllers/admin/mo_ogone__logfile.php',
        'mo_ogone__setup'                    => 'mo/mo_ogone/controllers/admin/mo_ogone__setup.php',
        //controllers (frontend)
        'mo_ogone__deferred_feedback'        => 'mo/mo_ogone/controllers/mo_ogone__deferred_feedback.php',
        'mo_ogone__order_error'              => 'mo/mo_ogone/controllers/mo_ogone__order_error.php',
        'mo_ogone__payment_form'             => 'mo/mo_ogone/controllers/mo_ogone__payment_form.php',
        'mo_ogone__template'                 => 'mo/mo_ogone/controllers/mo_ogone__template.php',
        //models
        'mo_ogone__order_number_reservation' => 'mo/mo_ogone/models/mo_ogone__order_number_reservation.php',
        'mo_ogone__payment_log'              => 'mo/mo_ogone/models/mo_ogone__payment_log.php',
        //adapter
        'mo_ogone__abstract_factory'                  => 'mo/mo_ogone/classes/adapter/mo_ogone__abstract_factory.php',
        'mo_ogone__sha_settings'                      => 'mo/mo_ogone/classes/adapter/mo_ogone__sha_settings.php',
        'mo_ogone__request_param_builder'             => 'mo/mo_ogone/classes/adapter/mo_ingenico__request_param_builder.php',
        'mo_ogone__alias_param_builder'               => 'mo/mo_ogone/classes/adapter/mo_ogone__alias_param_builder.php',
        'mo_ogone__order_redirect_param_builder'      => 'mo/mo_ogone/classes/adapter/mo_ogone__order_redirect_param_builder.php',
        'mo_ogone__order_direct_param_builder'        => 'mo/mo_ogone/classes/adapter/mo_ogone__order_direct_param_builder.php',
        'mo_ogone__hosted_tokenization_param_builder' => 'mo/mo_ogone/classes/adapter/mo_ogone__hosted_tokenization_param_builder.php',
        //core
        'mo_ogone__config'                   => 'mo/mo_ogone/classes/mo_ogone__config.php',
        'mo_ogone__main'                     => 'mo/mo_ogone/classes/mo_ogone__main.php',
        'mo_ogone__logger'                     => 'mo/mo_ogone/classes/mo_ogone__logger.php',
        'mo_ogone__transaction_logger'       => 'mo/mo_ogone/classes/mo_ogone__transaction_logger.php',
    ),
    'blocks'      => array(
        array(
            'template'    => 'page/checkout/payment.tpl',
            'block'       => 'select_payment',
            'file'        => 'views/blocks/page/checkout/payment/select_payment.tpl'
        ),
        array(
            'template'    => 'order_overview.tpl',
            'block'       => 'admin_order_overview_export',
            'file'        => 'views/blocks/admin/admin_order_overview_export.tpl'
        ),
        array(
            'template'    => 'order_overview.tpl',
            'block'       => 'admin_order_overview_status',
            'file'        => 'views/blocks/admin/admin_order_overview_status.tpl'
        ),
    ),
    'templates'   => array(
        'mo_ogone__interface.tpl'                      => 'mo/mo_ogone/views/admin/tpl/mo_ogone__interface.tpl',
        'mo_ogone__logfile.tpl'                        => 'mo/mo_ogone/views/admin/tpl/mo_ogone__logfile.tpl',
        'mo_ogone__setup.tpl'                          => 'mo/mo_ogone/views/admin/tpl/mo_ogone__setup.tpl',
        'mo_ogone__payment_creditcard.tpl'             => 'mo/mo_ogone/views/azure/tpl/page/checkout/inc/mo_ogone__payment_creditcard.tpl',
        'mo_ogone__payment_creditcard_form_fields.tpl' => 'mo/mo_ogone/views/azure/tpl/page/checkout/inc/mo_ogone__payment_creditcard_form_fields.tpl',
        'mo_ogone__payment_invoice.tpl'                => 'mo/mo_ogone/views/azure/tpl/page/checkout/inc/mo_ogone__payment_invoice.tpl',
        'mo_ogone__payment_one_page.tpl'               => 'mo/mo_ogone/views/azure/tpl/page/checkout/mo_ogone__payment_one_page.tpl',
        'dynamic.tpl'                                  => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/dynamic.tpl',
        'dynamic_mobile.tpl'                           => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/dynamic_mobile.tpl',
        'order_error.tpl'                              => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/order_error.tpl',
        'payment_form.tpl'                             => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/payment_form.tpl',

    ),
);

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
    'title'       => 'Ogone 3.0',
    'description' => array(
        'de' => 'Ogone Payment'
    ),
    'thumbnail'   => 'logo.png',
    'version'     => '3.0',
    'author'      => '<a href="http://www.mediaopt.de" target="_blank">mediaopt.</a>',
    'url'         => 'http://www.mediaopt.de',
    'email'       => 'oxid@mediaopt.de',
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
        //core
        'mo_ogone__config'                   => 'mo/mo_ogone/classes/mo_ogone__config.php',
        'mo_ogone__feedback_handler'         => 'mo/mo_ogone/classes/mo_ogone__feedback_handler.php',
        'mo_ogone__logger'                   => 'mo/mo_ogone/classes/mo_ogone__logger.php',
        'mo_ogone__main'                     => 'mo/mo_ogone/classes/mo_ogone__main.php',
        'mo_ogone__util'                     => 'mo/mo_ogone/classes/mo_ogone__util.php',        
    ),
    'blocks'      => array(
        array(
            'template'    => 'page/checkout/payment.tpl',
            'block'       => 'select_payment',
            'file'        => 'views/blocks/page/checkout/payment/select_payment.tpl'
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
        'order_error.tpl'                              => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/order_error.tpl',
        'payment_form.tpl'                             => 'mo/mo_ogone/views/azure/tpl/page/mo_ogone/payment_form.tpl',
    ),
    'settings'    => array(
        array('group' => 'mo_ogone__config', 'name'  => 'mo_ogone__isLiveMode',
            'type'  => 'bool'),
        array('group' => 'mo_ogone__config', 'name'  => 'mo_ogone__logLevel',
            'type'  => 'select', 'constraints' => 'DEBUG|INFO|ERROR'),
    ),
);
<?php

/**
 * Metadata version
 */
$sMetadataVersion = '0.9';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'mo_ogone_3.0',
    'title'       => 'ogone_3.0',
    'description' => array(
        'de' => ''
    ),
    'thumbnail'   => 'logo.png',
    'version'     => '',
    'author'      => '<a href="http://www.mediaopt.de" target="_blank">mediaopt.</a>',
    'url'         => '',
    'email'       => '',
    'extend'      => array(
        //components
        //controllers (admin)
        //controllers (frontend)
        	//'order' => 'mo/mo_ogone_3.0/controllers/mo_ogone_3.0__order',
        //models
        	//'oxorder' => 'mo/mo_ogone_3.0/models/mo_ogone_3.0__oxorder',
        //core
    ),
    'files'       => array(
    ),
    'blocks'      => array(
        //array(
        //    'template' => 'page/checkout/payment.tpl',
        //    'block'=>'checkout_payment_errors',
        //    'file'=>'/views/blocks/checkout_payment_errors.tpl'),
    ),
    'templates'   => array(
        //'mo_ogone_3.0__admin__order.tpl' => 'mo/mo_ogone_3.0/views/admin/tpl/mo_ogone_3.0__admin__order.tpl',
    ),
    'settings'    => array(
        array('group' => 'mo_ogone_3.0__config', 'name'  => 'mo_ogone_3.0__isLiveMode',
            'type'  => 'bool'),
        array('group' => 'mo_ogone_3.0__config', 'name'  => 'mo_ogone_3.0__logLevel',
            'type'  => 'select', 'constraints' => 'DEBUG|INFO|ERROR'),
    ),
);

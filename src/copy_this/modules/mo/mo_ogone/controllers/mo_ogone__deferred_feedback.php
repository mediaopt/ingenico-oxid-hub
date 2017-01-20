<?php

use Mediaopt\Ogone\Sdk\Main;

/**
 * $Id: mo_ogone__deferred_feedback.php 7 2012-12-12 10:44:58Z martin $ 
 */
class mo_ogone__deferred_feedback extends oxUBase
{

    public function render()
    {
        // process deferred feedback
        $authenticator = Main::getInstance()->getService("Authenticator");
        $authenticator->setShaSettings(oxNew('mo_ogone__sha_settings')->build());
        /* @var $response Mediaopt\Ogone\Sdk\Model\OgoneResponse */
        $response = Main::getInstance()->getService("DeferredFeedback")->handleResponse($authenticator);
        if ($response !== null) {
            $order = oxNew('oxorder');

            if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__transid_param') === 'PAYID') {
                $order->mo_ogone__loadByNumber($response->getPayId());
                if (!$order->isLoaded()) {
                    Main::getInstance()->getLogger()->error("Could not load order: " . $response->getPayId());
                    $order->mo_ogone__loadByNumber($response->getOrderId());
                    if (!$order->isLoaded()) {
                        Main::getInstance()->getLogger()->error("Could not load order: " . $response->getOrderId());
                        Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams());
                        return;
                    }
                }
                
            } else {
                $order->mo_ogone__loadByNumber($response->getOrderId());
                if (!$order->isLoaded()) {
                    Main::getInstance()->getLogger()->error("Could not load order: " . $response->getOrderId());
                    $order->mo_ogone__loadByNumber($response->getPayId());
                    if (!$order->isLoaded()) {
                        Main::getInstance()->getLogger()->error("Could not load order: " . $response->getPayId());
                        Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams());
                        return;
                    }
                }
            }
            
            Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams(), $order->oxorder__oxordernr->value);
            $order->mo_ogone__updateOrderStatus($response->getStatus());
        }
        // offline request from ogone, no further processing needed
        exit;
    }

}

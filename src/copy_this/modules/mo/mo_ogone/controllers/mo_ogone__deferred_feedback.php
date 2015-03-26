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
        /* @var $response Mediaopt\Ogone\Sdk\Model\OgoneResponse */
        $response = Main::getInstance()->getService("DeferredFeedback")->handleResponse();
        if ($response !== null) {
            $order = oxNew('oxorder');

            $order->mo_ogone__loadByNumber($response->getOrderId());
            if (!$order->isLoaded()) {
                Main::getInstance()->getLogger()->error("Could not load order: " . $response->getOrderId());
                Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams());
                return;
            }
            Main::getInstance()->getService('StoreTransactionFeedback')->store($response->getAllParams(), $order->oxorder__oxordernr->value);
            $order->mo_ogone__updateOrderStatus($response->getStatus()->getStatusCode());
        }
        // offline request from ogone, no further processing needed
        exit;
    }

}

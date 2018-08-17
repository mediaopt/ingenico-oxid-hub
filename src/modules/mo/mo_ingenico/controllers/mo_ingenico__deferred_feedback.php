<?php

use Mediaopt\Ingenico\Sdk\Main;

/**
 * $Id: mo_ingenico__deferred_feedback.php 7 2012-12-12 10:44:58Z martin $ 
 */
class mo_ingenico__deferred_feedback extends oxUBase
{

    /**
     * simplify $order->isLoadedCheck and handling
     */
    public function render()
    {
        $link = $this->getViewConfig()->getSslSelfLink();
        Main::getInstance()->getLogger()->warning("The use of {$link}cl=mo_ingenico__deferred_feedback as the URL for deferred Feedback is deprecated.
            Use {$link}cl=order&fnc=mo_ingenico__fncHandleDeferredFeedback instead.
            Please change the URL in the PSP Backend.");
        // process deferred feedback
        $authenticator = Main::getInstance()->getService('Authenticator');
        $authenticator->setShaSettings(oxNew('mo_ingenico__sha_settings')->build());
        /* @var $response Mediaopt\Ingenico\Sdk\Model\IngenicoResponse */
        $response = Main::getInstance()->getService('DeferredFeedback')->handleResponse($authenticator);
        if ($response !== null) {
            $order = oxNew('oxorder');

            if (oxRegistry::getConfig()->getShopConfVar('mo_ingenico__transid_param') === 'PAYID') {
                $order->mo_ingenico__loadByNumber($response->getPayId());
                if (!$order->isLoaded()) {
                    Main::getInstance()->getLogger()->error('Could not load order: ' . $response->getPayId());
                    $order->mo_ingenico__loadByNumber($response->getOrderId());
                    if (!$order->isLoaded()) {
                        Main::getInstance()->getLogger()->error('Could not load order: ' . $response->getOrderId());
                        oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams());
                        return;
                    }
                }
                
            } else {
                $order->mo_ingenico__loadByNumber($response->getOrderId());
                if (!$order->isLoaded()) {
                    Main::getInstance()->getLogger()->error('Could not load order: ' . $response->getOrderId());
                    $order->mo_ingenico__loadByNumber($response->getPayId());
                    if (!$order->isLoaded()) {
                        Main::getInstance()->getLogger()->error('Could not load order: ' . $response->getPayId());
                        oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams());
                        return;
                    }
                }
            }

            oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), $order->oxorder__oxordernr->value);
            $order->mo_ingenico__updateOrderStatus($response->getStatus());
        }
        // offline request from ingenico, no further processing needed
        exit;
    }

}

<?php
/**
 * Date: 20.04.17
 * Time: 16:25
 */

class mo_ogone__monolog_processor {

    private $sessionId;
    private $username;

    public function __construct()
    {
        $this->sessionId = oxRegistry::getSession()->getId();
        if ($user = oxRegistry::getSession()->getUser()) {
            $this->username = $user->oxuser__oxusername->value;
        }
    }

    public function __invoke(array $record)
    {
        $record['extra']['sid'] = $this->sessionId;
        if ($this->username) {
            $record['extra']['username'] = $this->username;
        }
        if ($basket = oxRegistry::getSession()->getBasket()) {
            $basketAmount = oxNew('mo_ogone__helper')->getFormattedOrderAmount($basket);
        } else {
            $basketAmount = 'BasketNotPresent';
        }
        $record['extra']['basketAmount'] = $basketAmount;
        return $record;
    }
}
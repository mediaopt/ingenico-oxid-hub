<?php

use Mediaopt\Ogone\Sdk\Main;

/**
 * $Id: mo_ogone__payment_form.php 55 2014-12-01 10:28:24Z martin $ 
 */
// Step 4.5
class mo_ogone__payment_form extends oxUBase
{

    protected $_sThisTemplate = 'payment_form.tpl';

    public function render()
    {

        // prevent basketitem isBuyable validation
        // use of basket between order and thankyou views causes errors, when buying last item in stock
        oxRegistry::getConfig()->setConfigParam('mo_ogone__prevent_recalculate', true);
        oxRegistry::getSession()->getBasket()->afterUpdate();

        parent::render();
        
        $this->_aViewData['mo_ogone__form_action'] = $this->getRedirectUrl();
        $this->_aViewData['mo_ogone__hidden_fields'] = Main::getInstance()->getService("OrderRedirectGateway")->buildParams();
        $this->_aViewData['mo_ogone__debug'] = mo_ogone__main::getInstance()->getOgoneConfig()->debug;

        return $this->_sThisTemplate;
    }
    
    protected function getRedirectUrl() {
        $part1 = '';
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__isLiveMode')) {
            $part1 = 'prod';
        } else {
            $part1 = 'test';
        }
        $part2 = '';
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_utf8')) {
            $part2 = '_utf8';
        }
        return 'https://secure.ogone.com/ncol/'. $part1 .'/orderstandard'. $part2 .'.asp';
    }

}

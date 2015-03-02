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

        // load order
        //$order  = oxNew('oxorder');
        //$loaded = $order->load(oxRegistry::getSession()->getVariable('sess_challenge'));
        //if (!$loaded)
        //{
        //  throw new oxSystemComponentException('Could not load order');
        //}

        /* @var $order oxOrder */
        $order = oxNew("oxOrder");
        $order->mo_ogone__initBeforePayment($this->getUser(), oxRegistry::getSession()->getBasket());

        $this->_aViewData['mo_ogone__form_action'] = $this->getConfig()->getConfigParam('mo_ogone__gateway_url_redirect');
        $this->_aViewData['mo_ogone__hidden_fields'] = Main::getInstance()->getService("RequestParamBuilder")->build($order);
        $this->_aViewData['mo_ogone__debug'] = mo_ogone__main::getInstance()->getOgoneConfig()->debug;

        return $this->_sThisTemplate;
    }

}

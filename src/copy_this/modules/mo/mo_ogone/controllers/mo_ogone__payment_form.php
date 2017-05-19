<?php


/**
 * custom payment form page, embedds link to psp payment page
 * Step 4.5
 */
class mo_ogone__payment_form extends oxUBase
{

    /**
     * tpl file for payment form controller
     *
     * @var string
     */
    protected $_sThisTemplate = 'flow_payment_form.tpl';

    /**
     * tpl file for payment form controller azure style
     *
     * @deprecated will be removed with next major release
     * @var string
     */
    protected $_sThisTemplateAzure = 'payment_form.tpl';

    /**
     * prepare parameters and redirect link
     *
     * @return string
     * @extends render
     */
    public function render()
    {
        // prevent basketitem isBuyable validation
        // use of basket between order and thankyou views causes errors, when buying last item in stock
        oxRegistry::getConfig()->setConfigParam('mo_ogone__prevent_recalculate', true);
        oxRegistry::getSession()->getBasket()->afterUpdate();

        parent::render();

        $this->_aViewData['mo_ogone__form_action'] = $this->getRedirectUrl();
        $this->_aViewData['mo_ogone__hidden_fields'] = oxNew('mo_ogone__order_redirect_param_builder')->build()->getParams();
        $this->_aViewData['mo_ogone__debug'] = $this->getConfig()->getConfigParam('mo_ogone__debug_mode');

        if($this->getViewConfig()->getActiveTheme() === 'azure')
        {
            return $this->_sThisTemplateAzure;
        }

        return $this->_sThisTemplate;
    }

    /**
     * prepare redirect link according to config
     *
     * @return string
     */
    protected function getRedirectUrl()
    {
        $mode = 'test';
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__isLiveMode')) {
            $mode = 'prod';
        }
        $encoding = '';
        if (oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_utf8')) {
            $encoding = '_utf8';
        }
        return 'https://secure.ogone.com/ncol/' . $mode . '/orderstandard' . $encoding . '.asp';
    }

}

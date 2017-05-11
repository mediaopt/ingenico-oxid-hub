<?php

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;

/**
 * User: martinb
 * Date: 05.05.17
 * Time: 11:31
 */

class mo_ogone__aftersales extends oxAdminDetails
{

    /**
     * @var oxOrder
     */
    protected $_oEditObject;

    /**
     * Executes parent method parent::render(), creates oxorder and oxvoucherlist
     * objects, appends voucherlist information to order object and passes data
     * to Smarty engine, returns name of template file "order_article.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        if ($oOrder = $this->getEditObject()) {
            $this->_aViewData["edit"] = $oOrder;
            $this->_aViewData["aProductVats"] = $oOrder->getProductVats(true);
        }

        return 'mo_ogone__aftersales.tpl';
    }

    /**
     * Returns editable order object
     *
     * @return oxorder
     */
    public function getEditObject()
    {
        $soxId = $this->getEditObjectId();
        if ($this->_oEditObject === null && isset($soxId) && $soxId != "-1") {
            $this->_oEditObject = oxNew("oxorder");
            $this->_oEditObject->load($soxId);
        }

        return $this->_oEditObject;
    }

    public function refund()
    {
        if (!$articles = oxRegistry::getConfig()->getRequestParameter('aOrderArticles')) {
            $articles = [];
        }
        $includeShipment = (bool)oxRegistry::getConfig()->getRequestParameter('includeShipment');
        $includeGiftcard = (bool)oxRegistry::getConfig()->getRequestParameter('includeGiftcard');
        $oxOrder = $this->getEditObject();
        $paramBuilder = oxNew('mo_ogone__aftersale_param_builder');
        $params = $paramBuilder->build($oxOrder, $articles, 'RFD', $includeShipment, $includeGiftcard);
        $response = Main::getInstance()->getService('AfterSales')->call($paramBuilder->getUrl(), $params);
        $xml = simplexml_load_string($response);

        /* @var $response OgoneResponse */
        $response = Main::getInstance()->getService('AfterSales')->handleResponse($xml);
        oxNew('mo_ogone__transaction_logger')->storeTransaction($response->getAllParams(), $oxOrder->oxorder__oxordernr->value);
        if ($response->hasError()) {
            $this->_aViewData["error_message"] = $response->getError()->getTranslatedStatusMessage().($response->getErrorPlus()?' ('.$response->getErrorPlus().')':'');
        } else {
            $this->_aViewData["success_message"] = $response->getStatus()->getTranslatedStatusMessage();
        }
    }

    public function capture()
    {
        if (!$articles = oxRegistry::getConfig()->getRequestParameter('aOrderArticles')) {
            $articles = [];
        }
        $includeShipment = (bool)oxRegistry::getConfig()->getRequestParameter('includeShipment');
        $includeGiftcard = (bool)oxRegistry::getConfig()->getRequestParameter('includeGiftcard');
        $oxOrder = $this->getEditObject();
        $paramBuilder = oxNew('mo_ogone__aftersale_param_builder');
        $params = $paramBuilder->build($oxOrder, $articles, 'SAL', $includeShipment, $includeGiftcard);
        $response = Main::getInstance()->getService('AfterSales')->call($paramBuilder->getUrl(), $params);
        $xml = simplexml_load_string($response);

        /* @var $response OgoneResponse */
        $response = Main::getInstance()->getService('AfterSales')->handleResponse($xml);
        oxNew('mo_ogone__transaction_logger')->storeTransaction($response->getAllParams(), $oxOrder->oxorder__oxordernr->value);
        if ($response->hasError()) {
            $this->_aViewData["error_message"] = $response->getError()->getTranslatedStatusMessage().($response->getErrorPlus()?' ('.$response->getErrorPlus().')':'');
        } else {
            $this->_aViewData["success_message"] = $response->getStatus()->getTranslatedStatusMessage();
        }
    }
}
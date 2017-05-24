<?php

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;

/**
 * User: martinb
 * Date: 05.05.17
 * Time: 11:31
 */

class mo_ingenico__refund extends oxAdminDetails
{

    const MO_INGENICO__REFUND_OPERATION = 'RFD';

    /**
     * @var oxOrder
     */
    protected $_oEditObject;

    /**
     * Executes parent method parent::render() and passes data
     * to Smarty engine, returns name of template file "mo_ingenico__refund.tpl".
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

        return 'mo_ingenico__refund.tpl';
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

    /**
     * controller function. Creates an aftersales operation to refund an amount depending on chosen
     * order articles, shipping costs and giftcards. Handles the response and presents the success or error
     * message to the user
     */
    public function refund()
    {
        if (!$articles = oxRegistry::getConfig()->getRequestParameter('aOrderArticles')) {
            $articles = [];
        }
        $includeShipment = (bool)oxRegistry::getConfig()->getRequestParameter('includeShipment');
        $includeGiftcard = (bool)oxRegistry::getConfig()->getRequestParameter('includeGiftcard');
        $oxOrder = $this->getEditObject();
        $paramBuilder = oxNew('mo_ingenico__aftersale_param_builder');
        $params = $paramBuilder->build($oxOrder, $articles, self::MO_INGENICO__REFUND_OPERATION, $includeShipment, $includeGiftcard);
        $aftersalesGateway = Main::getInstance()->getService('DirectGateway')->setType('AfterSales');
        $response = $aftersalesGateway->call($paramBuilder->getUrl(), $params);
        $xml = simplexml_load_string($response);

        /* @var $response IngenicoResponse */
        $response = $aftersalesGateway->handleResponse($xml);
        oxNew('mo_ingenico__transaction_logger')->storeTransaction($response->getAllParams(), $oxOrder->oxorder__oxordernr->value);
        if ($response->hasError()) {
            $this->_aViewData["error_message"] = $response->getError()->getTranslatedStatusMessage().($response->getErrorPlus()?' ('.$response->getErrorPlus().')':'');
        } else {
            $this->_aViewData["success_message"] = $response->getStatus()->getTranslatedStatusMessage();
        }
    }

}
<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;
use Mediaopt\Ogone\Sdk\Model\StatusType;
use Mediaopt\Ogone\Sdk\Model\Url;

/**
 * $Id: $
 */
class OrderDirectGateway extends AbstractService
{

    public function call()
    {
        /* @var $model Url */
        $model = $this->getAdapter()->getFactory("OrderDirectURL")->build();
        $url = $model->getUrl();
        $params = $this->buildParams();
        return $this->getClient()->call($url, $params);
    }

    public function buildParams()
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("OrderDirectParamBuilder")->build();
        return $model->getParams();
    }

    // contains no sha-return because of server-to-server communication
    public function handleResponse($xml)
    {
        if (empty($xml)) {
            $this->getAdapter()->getLogger()->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            return $status;
        }
        //convert to array
        $data = $this->getResponseData($xml);
        $this->getAdapter()->getLogger()->info("handleOrderRedirectResponse: " . var_export($data, true));
        //no response: curl timeout etc... we assume an uncertain status, redirect to error view
        if (count($data) === 0) {
            $this->getAdapter()->getLogger()->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            return $status;
        }

        /* @var $status Status */
        $status = Main::getInstance()->getService("Status");

        //check for error-code
        if (!isset($data['STATUS'])) {
            $status->setStatusCode(StatusType::INCOMPLETE_OR_INVALID);
            $this->getAdapter()->getLogger()->error('DirectLink-Response contains no STATUS-Property!');
        } else {
            $status->setStatusCode($data['STATUS']);
            $this->getAdapter()->getLogger()->info('GOT Ogone-Status in Direct-Link-Response: ' . $status->getStatusCode());
        }

        if ($status->isThankyouStatus()) {
            $this->getAdapter()->getLogger()->info('Ogone DirectLink Success (' . $status->getStatusCode() . ')');
            return $status;
        }

        $errorMessage = $status->getTranslatedStatusMessage();
        if ($data['NCERROR']) {
            $status = Main::getInstance()->getService("Status")->usingStatusCode($data['NCERROR']);
            $errorMessage = $status->getTranslatedStatusMessage();
        }
        $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);
        return $status;
    }

    public function getResponseData($xml){
        $data = array();
        foreach ($xml->attributes() as $key => $value) {
            $data[(string) $key] = (string) $value;
        }
        return $data;
    }
    
}

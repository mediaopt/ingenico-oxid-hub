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
        /* @var $response \Mediaopt\Ogone\Sdk\Model\OgoneResponse */
        $response = Main::getInstance()->getModel('OgoneResponse');
        if (empty($xml)) {
            $this->getAdapter()->getLogger()->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
            $statusCode = (int) StatusType::INCOMPLETE_OR_INVALID;
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode($statusCode);
            $response->setStatusCode($statusCode);
            $response->setStatusService($status);
            return $response;
        }
        //convert to array
        $data = $this->getResponseData($xml);
        $this->getAdapter()->getLogger()->info("handleOrderRedirectResponse: " . var_export($data, true));
        //no response: curl timeout etc... we assume an uncertain status, redirect to error view
        if (count($data) === 0) {
            $this->getAdapter()->getLogger()->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
            $statusCode = (int) StatusType::INCOMPLETE_OR_INVALID;
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode($statusCode);
            $response->setStatusCode($statusCode);
            $response->setStatusService($status);
            return $response;
        }
        $response->setAllParams($data);
        /* @var $status Status */
        $status = Main::getInstance()->getService("Status");

        //check for error-code
        if (!isset($data['STATUS'])) {
            $this->getAdapter()->getLogger()->error('DirectLink-Response contains no STATUS-Property!');
            $statusCode = (int) StatusType::INCOMPLETE_OR_INVALID;
            $status->setStatusCode($statusCode);
            $response->setStatusCode($statusCode);
            $response->setStatusService($status);
            return $response;
        } else {
            $statusCode = $data['STATUS'];
            $status->setStatusCode($statusCode);
            $response->setStatusCode($statusCode);
            $response->setStatusService($status);
            $this->getAdapter()->getLogger()->info('GOT Ogone-Status in Direct-Link-Response: ' . $status->getStatusCode());
        }

        if ($status->isThankyouStatus()) {
            $this->getAdapter()->getLogger()->info('Ogone DirectLink Success (' . $status->getStatusCode() . ')');
            return $response;
        }

        $errorMessage = $status->getTranslatedStatusMessage();
        if ($data['NCERROR']) {
            $statusCode = $data['NCERROR'];
            $status->setStatusCode($statusCode);
            $response->setStatusCode($statusCode);
            $response->setStatusService($status);
            $errorMessage = $status->getTranslatedStatusMessage();
        }
        $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);
        return $response;
    }

    public function getResponseData($xml){
        $data = array();
        foreach ($xml->attributes() as $key => $value) {
            $data[(string) $key] = (string) $value;
        }
        return $data;
    }
    
}

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
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            $response->setStatus($status);
            return $response;
        }
        //convert to array
        $response = $this->getResponse($xml);
        $this->getAdapter()->getLogger()->info("handleOrderDirectResponse: " . var_export($response->getAllParams(), true));
        
        $statusDebugInfo = 'Ogone-Status: ' .
                $response->getStatus()->getStatusTextForCode() . ' (' . $response->getStatus()->getStatusCode() . ')';

        if ($response->getStatus()->isThankyouStatus()) {
            $this->getAdapter()->getLogger()->info('Ogone Transaction Success - ' . $statusDebugInfo);
            return $response;
        }

        if ($response->hasError()) {
            $errorMessage = $response->getError()->getTranslatedStatusMessage();
        } else {
            $errorMessage = $response->getStatus()->getTranslatedStatusMessage();
        }

        $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);

        return $response;
    }

    /**
     * 
     * @return \Mediaopt\Ogone\Sdk\Model\OgoneResponse
     */
    public function getResponse($xml)
    {
        $data = array();
        foreach ($xml->attributes() as $key => $value) {
            $data[strtoupper($key)] = (string) $value;
        }
        $this->getAdapter()->getLogger()->info('DirectGateway Parsed Response: ' . json_encode($data));
        return $this->getAdapter()->getFactory("OgoneResponse")->buildFromData($data);
    }
    
}

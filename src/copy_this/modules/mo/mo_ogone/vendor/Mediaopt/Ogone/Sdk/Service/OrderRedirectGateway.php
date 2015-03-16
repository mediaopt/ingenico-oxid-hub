<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;
use Mediaopt\Ogone\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class OrderRedirectGateway extends AbstractService
{

    public function buildParams()
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("OrderRedirectParamBuilder")->build();
        return $model->getParams();
    }

    public function handleResponse()
    {
        $response = $this->getResponse();
        $this->getAdapter()->getLogger()->info("handleOrderRedirectResponse: " . var_export($response->getAllParams(), true));

        if (!Main::getInstance()->getService("Authenticator")->authenticateRequest()) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error("SHA-OUT-Mismatch: " . var_export($response->getAllParams(), true));
            $statusCode = (int) StatusType::INCOMPLETE_OR_INVALID;
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode($statusCode);
            $response->setStatusCode($statusCode);
            $response->setStatusService($status);
            return $response;
        }

        
        $statusDebugInfo = 'Ogone-Status: ' .
                $response->getStatusService()->getStatusTextForCode() . ' (' . $response->getStatusCode() . ')';

        if ($response->getStatusService()->isThankyouStatus()) {
            $this->getAdapter()->getLogger()->info('Ogone Transaction Success - ' . $statusDebugInfo);
            return $response;
        }

        $errorMessage = $response->getStatusService()->getTranslatedStatusMessage();
        if ($response->getAllParams()['NCERROR']) {
            $statusCode = (int) $response->getAllParams()['NCERROR'];
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode($statusCode);
            $response->setStatusCode($statusCode);
            $response->setStatusService($status);
            $errorMessage = $status->getTranslatedStatusMessage();
        }

        $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);

        return $response;
    }
    
    /**
     * 
     * @return \Mediaopt\Ogone\Sdk\Model\OgoneResponse
     */
    public function getResponse()
    {
        return $this->getAdapter()->getFactory("OgoneResponse")->build();
    }
}

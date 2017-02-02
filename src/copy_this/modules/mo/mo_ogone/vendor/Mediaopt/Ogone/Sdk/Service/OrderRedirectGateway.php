<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class OrderRedirectGateway extends AbstractService
{

    public function handleResponse(Authenticator $authenticator)
    {
        $response = $this->getResponse();
        $this->getAdapter()->getLogger()->info('handleOrderRedirectResponse: ' . var_export($response->getAllParams(), true));

        if (!$authenticator->authenticateRequest()) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error('SHA-OUT-Mismatch: ' . var_export($response->getAllParams(), true));
            $status = Main::getInstance()->getService('Status')
                    ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            $response->setStatus($status);
            $response->setError($status);
            return $response;
        }

        
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
    public function getResponse()
    {
        return $this->getAdapter()->getFactory('OgoneResponse')->build();
    }
}

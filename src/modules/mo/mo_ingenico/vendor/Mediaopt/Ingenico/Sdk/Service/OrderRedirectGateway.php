<?php

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class OrderRedirectGateway extends AbstractService
{

    public function handleResponse(Authenticator $authenticator)
    {
        $response = $this->getResponse();
        $this->getAdapter()->getLogger()->info('handleOrderRedirectResponse',$response->getAllParams());

        if (!$authenticator->authenticateRequest()) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error('SHA-OUT-Mismatch', $response->getAllParams());
            $status = Main::getInstance()->getService('Status')
                    ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            $response->setStatus($status);
            $response->setError($status);
            return $response;
        }

        
        $statusDebugInfo = 'Ingenico-Status: ' .
                $response->getStatus()->getStatusTextForCode() . ' (' . $response->getStatus()->getStatusCode() . ')';

        if ($response->getStatus()->isThankyouStatus()) {
            $this->getAdapter()->getLogger()->info('Ingenico Transaction Success - ' . $statusDebugInfo);
            return $response;
        }

        if ($response->hasError()) {
            $errorMessage = $response->getError()->getTranslatedStatusMessage();
        } else {
            $errorMessage = $response->getStatus()->getTranslatedStatusMessage();
        }

        $this->getAdapter()->getLogger()->info('Ingenico Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);

        return $response;
    }
    
    /**
     * 
     * @return \Mediaopt\Ingenico\Sdk\Model\IngenicoResponse
     */
    public function getResponse()
    {
        return $this->getAdapter()->getFactory('IngenicoResponse')->build();
    }
}

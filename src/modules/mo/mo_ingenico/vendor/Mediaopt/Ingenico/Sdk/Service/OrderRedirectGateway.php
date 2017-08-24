<?php

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;

/**
 * $Id: $
 */
class OrderRedirectGateway extends AbstractService
{

    public function handleResponse(Authenticator $authenticator)
    {
        $response = $this->getResponse();
        $this->getAdapter()->getLogger()->info('handleOrderRedirectResponse',$response->getAllParams());

        if (!$response->hasError() && $this->checkForMandatoryFields($response)) {
            $this->getAdapter()->getLogger()->error('Mandatory fields missing!', $response->getAllParams());
            return $response->markAsIncomplete();
        }
        if (!$authenticator->authenticateRequest()) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error('SHA-OUT-Mismatch', $response->getAllParams());
            return $response->markAsIncomplete();
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

    protected function checkForMandatoryFields(IngenicoResponse $response)
    {
        return null === $response->getAmount()
            || null === $response->getStatus()
            || null === $response->getSessionChallenge()
            || null === $response->getPayId()
            || null === $response->getOrderId();
    }
}

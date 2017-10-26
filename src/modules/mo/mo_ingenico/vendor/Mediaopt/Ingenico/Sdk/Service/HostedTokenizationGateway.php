<?php

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;

/**
 * $Id: $
 */
class HostedTokenizationGateway extends AbstractService
{

    /**
     * @param Authenticator $authenticator
     * @return IngenicoResponse
     */
    public function handleResponse(Authenticator $authenticator)
    {
        /* @var $response IngenicoResponse */
        $response = $this->getAdapter()->getFactory('IngenicoResponse')->build();
        $this->getAdapter()->getLogger()->info('handleHostedTokenizationResponse',$response->getAllParams());
        if (!$response->hasError() && $this->checkForMandatoryFields($response)) {
            $this->getAdapter()->getLogger()->error('Mandatory fields missing!', $response->getAllParams());
            return $response->markAsIncomplete();
        }
        if (!$authenticator->authenticateRequest('HostedTokenizationPage')) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error('SHA-OUT-Mismatch,', $response->getAllParams());
            return $response->markAsIncomplete();
        }
        
        if ($response->hasError()) {
            $this->getAdapter()->getLogger()->info('Ingenico Transaction Failure: ' . 
                    $response->getError()->getStatusCode() . ' - ' . $response->getError()->getTranslatedStatusMessage());
        }
        return $response;     
    }

    /**
     * check if the mandatory fields alias and status are set
     *
     * @param IngenicoResponse $response
     *
     * @return bool
     */
    protected function checkForMandatoryFields(IngenicoResponse $response)
    {
        return null === $response->getAlias()
            || null === $response->getStatus();
    }
}

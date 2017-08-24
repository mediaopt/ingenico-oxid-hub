<?php

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;

/**
 * $Id: $
 */
class AliasGateway extends AbstractService
{

    /**
     *
     * @param Authenticator $authenticator
     * @return IngenicoResponse An ErrorStateObject if there is an error, null otherwise
     */
    public function handleResponse(Authenticator $authenticator)
    {
        /* @var $response IngenicoResponse */
        $response = $this->getAdapter()->getFactory('IngenicoResponse')->build();
        $this->getAdapter()->getLogger()->info('handleAliasResponse', $response->getAllParams());
        if (!$response->hasError() && $this->checkForMandatoryFields($response)) {
            $this->getAdapter()->getLogger()->error('Mandatory fields missing!', $response->getAllParams());
            return $response->markAsIncomplete();
        }
        if (!$authenticator->authenticateRequest('AliasGateway')) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error('SHA-OUT-Mismatch',$response->getAllParams());
            return $response->markAsIncomplete();
        }
        
        if ($response->hasError()) {
            $this->getAdapter()->getLogger()->info('Ingenico Transaction Failure: ' . 
                    $response->getError()->getStatusCode() . ' - ' . $response->getError()->getTranslatedStatusMessage());
        }
        return $response;     
    }

    protected function checkForMandatoryFields(IngenicoResponse $response)
    {
        return null === $response->getAlias()
            || null === $response->getStatus();
    }
}

<?php

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;
use Mediaopt\Ingenico\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class AliasGateway extends AbstractService
{

    /**
     *
     * @param Authenticator $authenticator
     * @return Status An ErrorStateObject if there is an error, null otherwise
     */
    public function handleResponse(Authenticator $authenticator)
    {
        /* @var $response IngenicoResponse */
        $response = $this->getAdapter()->getFactory('IngenicoResponse')->build();
        $this->getAdapter()->getLogger()->info('handleAliasResponse', $response->getAllParams());
        if ($this->checkForMandatoryFields($response)) {
            $this->getAdapter()->getLogger()->error('Mandatory fields missing!', $response->getAllParams());
            $status = Main::getInstance()->getService('Status')
                          ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            $response->setStatus($status);
            $response->setError($status);
            return $response;
        }
        if (!$authenticator->authenticateRequest('AliasGateway')) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error('SHA-OUT-Mismatch',$response->getAllParams());
            $status = Main::getInstance()->getService('Status')
                    ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            $response->setStatus($status);
            $response->setError($status);
            return $response;
        }
        
        if ($response->hasError()) {
            $this->getAdapter()->getLogger()->info('Ingenico Transaction Failure: ' . 
                    $response->getError()->getStatusCode() . ' - ' . $response->getError()->getTranslatedStatusMessage());
        }
        return $response;     
    }

    protected function checkForMandatoryFields(IngenicoResponse $response)
    {
        return
            null === $response->getAlias()
            || null === $response->getStatus()
            ;
    }
}

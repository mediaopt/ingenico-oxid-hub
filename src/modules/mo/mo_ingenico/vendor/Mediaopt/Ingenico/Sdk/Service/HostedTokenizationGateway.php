<?php

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;
use Mediaopt\Ingenico\Sdk\Model\StatusType;

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
        
        if (!$authenticator->authenticateRequest('HostedTokenizationPage')) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error('SHA-OUT-Mismatch,', $response->getAllParams());
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

}

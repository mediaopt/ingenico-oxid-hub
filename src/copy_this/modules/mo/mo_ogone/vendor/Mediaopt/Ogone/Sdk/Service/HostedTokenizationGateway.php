<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;
use Mediaopt\Ogone\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class HostedTokenizationGateway extends AbstractService
{

    /**
     * @param Authenticator $authenticator
     * @return OgoneResponse
     */
    public function handleResponse(Authenticator $authenticator)
    {
        /* @var $response OgoneResponse */
        $response = $this->getAdapter()->getFactory('OgoneResponse')->build();
        $this->getAdapter()->getLogger()->info('handleHostedTokenizationResponse: ' . var_export($response->getAllParams(), true));
        
        if (!$authenticator->authenticateRequest('HostedTokenizationPage')) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error('SHA-OUT-Mismatch: ' . var_export($response->getAllParams(), true));
            $status = Main::getInstance()->getService('Status')
                    ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            $response->setStatus($status);
            $response->setError($status);
            return $response;
        }
        
        if ($response->hasError()) {
            $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . 
                    $response->getError()->getStatusCode() . ' - ' . $response->getError()->getTranslatedStatusMessage());
        }
        return $response;     
    }

}

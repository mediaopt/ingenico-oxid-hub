<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;

/**
 * $Id: $
 */
class DeferredFeedback extends AbstractService
{
    
    /**
     * 
     * @return Status An ErrorStateObject if there is an error, null otherwise
     */
    public function handleResponse(Authenticator $authenticator)
    {
        /* @var $response OgoneResponse */
        $response = $this->getAdapter()->getFactory("OgoneResponse")->build();
        $this->getAdapter()->getLogger()->info("handleDeferredFeedback: " . var_export($response->getAllParams(), true));

        if (!$authenticator->authenticateRequest()) {
            $this->getAdapter()->getLogger()->error("Could not update order status, because of SHA-OUT mismatch!");
            return null;
        }

        if ($response->getOrderId() === null or $response->getStatus() === null) {
            $this->getAdapter()->getLogger()->error("Could not update order status, because of missing request params!");
            return null;
        }
        return $response;

    }

}

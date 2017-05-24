<?php

namespace Mediaopt\Ingenico\Sdk\Service;


use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;

/**
 * $Id: $
 */
class DeferredFeedback extends AbstractService
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
        $this->getAdapter()->getLogger()->info('handleDeferredFeedback',$response->getAllParams());

        if (!$authenticator->authenticateRequest()) {
            $this->getAdapter()->getLogger()->error('Could not update order status, because of SHA-OUT mismatch!');
            return null;
        }

        if ($response->getOrderId() === null || $response->getStatus() === null) {
            $this->getAdapter()->getLogger()->error('Could not update order status, because of missing request params!');
            return null;
        }
        return $response;

    }

}

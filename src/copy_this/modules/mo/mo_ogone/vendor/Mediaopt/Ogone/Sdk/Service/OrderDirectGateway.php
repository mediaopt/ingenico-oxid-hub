<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Model\OgoneResponse;


/**
 * $Id: $
 */
class OrderDirectGateway extends DirectGateway
{

    protected function logResponse(OgoneResponse $response)
    {
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
    }

}

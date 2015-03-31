<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;
use Mediaopt\Ogone\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class AliasGateway extends AbstractService
{

    /**
     * 
     * @param type $paymentId
     * @return RequestParameters The Parameters for Calling the AliasGateway
     */
    public function buildParams($paymentId)
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("AliasParamBuilder")->build($paymentId);
        return $model->getParams();
    }
    
    /**
     * 
     * @return Status An ErrorStateObject if there is an error, null otherwise
     */
    public function handleResponse()
    {
        /* @var $response OgoneResponse */
        $response = $this->getAdapter()->getFactory("OgoneResponse")->build();
        $this->getAdapter()->getLogger()->info("handleAliasResponse: " . var_export($response->getAllParams(), true));
        
        if (!Main::getInstance()->getService("Authenticator")->authenticateRequest()) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error("SHA-OUT-Mismatch: " . var_export($response->getAllParams(), true));
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            $response->setStatus($status);
            return $response;
        }
        
        if ($response->hasError()) {
            $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . 
                    $response->getError()->getStatusCode() . ' - ' . $response->getError()->getTranslatedStatusMessage());
        }
        return $response;     
    }

}

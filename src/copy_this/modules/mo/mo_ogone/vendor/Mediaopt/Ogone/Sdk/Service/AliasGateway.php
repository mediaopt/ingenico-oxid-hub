<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

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
        /* @var $response \Mediaopt\Ogone\Sdk\Model\OgoneResponse */
        $response = $this->getAdapter()->getFactory("OgoneResponse")->build();
        $this->getAdapter()->getLogger()->info("handleAliasResponse: " . var_export($response->getAllParams(), true));
        if ($response->hasError()) {
            $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . 
                    $response->getError()->getStatusCode() . ' - ' . $response->getError()->getTranslatedStatusMessage());
        }
        return $response;     
    }

}

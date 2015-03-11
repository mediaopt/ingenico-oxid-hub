<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

/**
 * $Id: $
 */
class AliasGateway extends AbstractService
{

    public function buildParams($paymentId)
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("AliasParamBuilder")->build($paymentId);
        return $model->getParams();
    }
    
    public function handleResponse()
    {
        /* @var $responseParams RequestParameters */
        $requestParameters = $this->getAdapter()->getFactory("RequestParameters")->build();
        $this->getAdapter()->getLogger()->info("handleAliasResponse: " . var_export($requestParameters->getParams(), true));

        return $this->handleAliasGatewayErrorResponse($requestParameters->getParams());
        
    }
    
    public function handleAliasGatewayErrorResponse($requestParameters)
    {
        $error = '';
        
        if (!empty($requestParameters['NCErrorCardNo']) && $requestParameters['NCErrorCardNo'] != '0') {
            $error = $requestParameters['NCErrorCardNo'];
        } elseif (!empty($requestParameters['NCErrorCVC']) && $requestParameters['NCErrorCVC'] != '0') {
            $error = $requestParameters['NCErrorCVC'];
        } elseif (!empty($requestParameters['NCErrorED']) && $requestParameters['NCErrorED'] != '0') {
            $error = $requestParameters['NCErrorED'];
        } elseif (!empty($requestParameters['NCError']) && $requestParameters['NCError'] != '0') {
            $error = $requestParameters['NCError'];
        }

        if ($error) {
            /* @var $status Status */
            $status = Main::getInstance()->getService("Status")->usingStatusCode($error);
            $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . $status->getStatusCode() . ' - ' . $status->getTranslatedStatusMessage());
            return $status;
        }
        return null;
    }

}

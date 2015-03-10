<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;
use Mediaopt\Ogone\Sdk\Model\StatusType;

/**
 * $Id: $
 */
class OrderRedirectGateway extends AbstractService
{

    public function buildParams($order)
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("OrderRedirectParamBuilder")->build($order);
        return $model->getParams();
    }

    public function handleResponse()
    {
        /* @var $requestParameters RequestParameters */
        $requestParameters = $this->getAdapter()->getFactory("RequestParameters")->build();

        $this->getAdapter()->getLogger()->info("handleOrderRedirectResponse: " . var_export($requestParameters->getParams(), true));

        if (!Main::getInstance()->getService("Authenticator")->authenticateRequest()) {
            // no authentication, kick back to payment methods
            $this->getAdapter()->getLogger()->error("SHA-OUT-Mismatch: " . var_export($requestParameters->getParams(), true));
            $status = Main::getInstance()->getService("Status")
                    ->usingStatusCode((int) StatusType::SHA_IN_MISMATCH);
            return $status;
        }

        // handles redirections from the payment server back to the store
        /* @var $status Status */
        $status = Main::getInstance()->getService("Status")
                ->usingStatusCode((int) $requestParameters->getParams()['STATUS']);
        $statusDebugInfo = 'Ogone-Status: ' .
                $status->getStatusTextForCode() . ' (' . $status->getStatusCode() . ')';

        if ($status->isThankyouStatus()) {
            $this->getAdapter()->getLogger()->info('Ogone Transaction Success - ' . $statusDebugInfo);
            return $status;
        }

        $errorMessage = $status->getTranslatedStatusMessage();
        if ($_REQUEST['NCERROR']) {
            $status = Main::getInstance()->getService("Status")->usingStatusCode((int) $requestParameters->getParams()['NCERROR']);
            $errorMessage = $status->getTranslatedStatusMessage();
        }

        $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);

        return $status;
    }

}

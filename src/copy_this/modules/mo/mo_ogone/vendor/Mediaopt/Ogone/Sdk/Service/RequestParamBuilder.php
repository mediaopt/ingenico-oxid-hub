<?php

namespace Mediaopt\Ogone\Sdk\Service;

/**
 * $Id: $
 */
class RequestParamBuilder extends AbstractService
{
    public function buildOnePageOrderdirectParams($order)
    {
        /* @var Mediaopt\Ogone\Sdk\Model\RequestParameters $model */
        $model = $this->getAdapter()->getFactory("RequestParamBuilder")->buildOnePageOrderdirectParams($order);
        return $model->getParams();
    }
    
    public function build($order)
    {
        /* @var Mediaopt\Ogone\Sdk\Model\RequestParameters $model */
        $model = $this->getAdapter()->getFactory("RequestParamBuilder")->build($order);
        return $model->getParams();
    }
    
    public function buildAliasGatewayParams($paymentId)
    {
        /* @var Mediaopt\Ogone\Sdk\Model\RequestParameters $model */
        $model = $this->getAdapter()->getFactory("RequestParamBuilder")->buildAliasGatewayParams($paymentId);
        return $model->getParams();
    }
}

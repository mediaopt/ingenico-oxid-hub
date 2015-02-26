<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Model\RequestParameters;

/**
 * $Id: $
 */
class RequestParamBuilder extends AbstractService
{
    public function buildOnePageOrderdirectParams($order)
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("RequestParamBuilder")->buildOnePageOrderdirectParams($order);
        return $model->getParams();
    }
    
    public function build($order)
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("RequestParamBuilder")->build($order);
        return $model->getParams();
    }
    
    public function buildAliasGatewayParams($paymentId)
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("RequestParamBuilder")->buildAliasGatewayParams($paymentId);
        return $model->getParams();
    }
}

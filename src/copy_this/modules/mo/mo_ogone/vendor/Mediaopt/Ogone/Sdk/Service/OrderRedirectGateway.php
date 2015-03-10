<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Model\RequestParameters;

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
    
}

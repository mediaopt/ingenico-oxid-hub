<?php

namespace Mediaopt\Ogone\Sdk\Service;

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

}

<?php

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\Url;

/**
 * $Id: $
 */
class OrderDirectGateway extends AbstractService
{

    public function call($order)
    {
        /* @var $model Url */
        $model = $this->getAdapter()->getFactory("OrderDirectURL")->build();
        $url = $model->getUrl();
        $params = $this->buildParams($order);
        return $this->getClient()->call($url, $params);
    }

    public function buildParams($order)
    {
        /* @var $model RequestParameters */
        $model = $this->getAdapter()->getFactory("OrderDirectParamBuilder")->build($order);
        return $model->getParams();
    }
    
}

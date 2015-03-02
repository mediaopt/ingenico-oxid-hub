<?php
namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

use Mediaopt\Ogone\Adapter\Oxid_5\Factory\AbstractFactory;
use Mediaopt\Ogone\Sdk\Model\Url;

/**
 * assetUrl factory
 */
class OrderDirectURL extends AbstractFactory
{
    /**
     * generate asset url
     * @return Url
     */
    public function build()
    {
        /*@var $model Url */
        $model = $this->getSdkMain()->getModel('Url');
        $model->setUrl($this->getOxConfig()->getShopConfVar('mo_ogone__gateway_url_orderdir'));
        return $model;
    }
}

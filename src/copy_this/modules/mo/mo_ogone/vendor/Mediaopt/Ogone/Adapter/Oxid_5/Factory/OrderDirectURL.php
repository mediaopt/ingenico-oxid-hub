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
        $part1 = '';
        if ($this->getOxConfig()->getShopConfVar('mo_ogone__isLiveMode')) {
            $part1 = 'prod';
        } else {
            $part1 = 'test';
        }
        $part2 = '';
        if ($this->getOxConfig()->getShopConfVar('mo_ogone__use_utf8')) {
            $part2 = '_utf8';
        }
        
        $model->setUrl('https://secure.ogone.com/ncol/'. $part1 .'/orderdirect'. $part2 .'.asp');
        return $model;
    }
}

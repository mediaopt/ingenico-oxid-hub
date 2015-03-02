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
        if (!function_exists('curl_init')) {
            $message = 'curl is missing. Please install php curl extension.';
            mo_ogone__main::getInstance()->getLogger()->error($message);
            return false;
        }
        /* @var $model Url */
        $model = $this->getAdapter()->getFactory("OrderDirectURL")->build();
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $model->getUrl(),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(Main::getInstance()->getService("RequestParamBuilder")->buildOnePageOrderdirectParams($order)),
            CURLOPT_RETURNTRANSFER => 1,
            // mbe: @TODO direkten Zugriff auf OgoneConfig entfernen
            CURLOPT_TIMEOUT => \mo_ogone__main::getInstance()->getOgoneConfig()->curl_timeout,
        ));

        $result = curl_exec($ch);

        // check for curl error
        if ($result === false) {
            // curl error
            $errorNumber = curl_errno($ch);
            $errorMessage = curl_error($ch);

            $this->getAdapter()->getLogger()->error("Curl error: $errorMessage ($errorNumber)");
        }

        curl_close($ch);

        return $result;
    }

}

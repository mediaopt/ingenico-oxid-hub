<?php

/**
 * $Id: Authenticator.php 53 2015-02-23 11:34:26Z mbe $ 
 */

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

/**
 * Authenticates so called SHAOut-Requests
 *  SHAOut => Response from Ogone
 *  SHAIn  => Requests from Oxid to Ogone
 */
class Authenticator extends AbstractService
{

    /*public function __construct($requestParams, $signatureBuilder, $oxConfig, $logger)
    {
        $this->requestParams = $requestParams;
        $this->signatureBuilder = $signatureBuilder;
        $this->oxConfig = $oxConfig;
        $this->logger = $logger;
    }*/

    // TODO: HK: Check signature with umlauts and different encodings
    public function authenticateRequest()
    {
        /* @var $paramsModel RequestParameters */
        $paramsModel = $this->getAdapter()->getFactory("RequestParameters")->build();
        $params = $paramsModel->getParams();
        if (!$params['SHASIGN']) {
            $this->getAdapter()->getLogger()->error("SHA-Sign Parameter not present - please check Ogone-Backend settings (technical-information" .
                    " => transaction-feedback => I want to receive transaction feedback[...])", $params);
            return false;
        }
        
        $this->signatureBuilder = Main::getInstance()->getService("SignatureBuilder");
        $signature = $this->signatureBuilder->build(
                $this->signatureBuilder->filterResponseParams($params), $this->getAdapter()->getOxConfig()->getConfigParam('ogone_sSecureKeyOut'));

        $signature = $this->signatureBuilder->hash($signature);

        if ($params['SHASIGN'] == $signature) {
            return true;
        }

        $this->getAdapter()->getLogger()->error("SECURE KEY INCORRECT - Received from IP: " . $_SERVER["REMOTE_ADDR"], $params);
        return false;
    }

}

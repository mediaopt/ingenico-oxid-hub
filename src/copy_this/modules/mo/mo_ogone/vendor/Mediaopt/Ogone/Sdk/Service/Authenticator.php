<?php

/**
 * $Id: Authenticator.php 53 2015-02-23 11:34:26Z mbe $ 
 */

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;
use Mediaopt\Ogone\Sdk\Model\SHASettings;

/**
 * Authenticates so called SHAOut-Requests
 *  SHAOut => Response from Ogone
 *  SHAIn  => Requests from Oxid to Ogone
 */
class Authenticator extends AbstractService
{

    /**
     * @var SHASettings
     */
    protected $shaSettings;

    public function setShaSettings(SHASettings $shaSettings)
    {
        $this->shaSettings = $shaSettings;
    }

    public function authenticateRequest($type = "")
    {
        /* @var $paramsModel OgoneResponse */
        $paramsModel = $this->getAdapter()->getFactory('OgoneResponse')->build(true);
        $params = $paramsModel->getAllParams();
        if (!$params['SHASIGN']) {
            $this->getAdapter()->getLogger()->error('SHA-Sign Parameter not present - please check Ogone-Backend settings (technical-information' .
                ' => transaction-feedback => I want to receive transaction feedback[...])', $params);
            return false;
        }

        /** @var SignatureBuilder $signatureBuilder */
        $signatureBuilder = Main::getInstance()->getService('SignatureBuilder');
        $signatureBuilder->setShaSettings($this->shaSettings);
        $signature = $signatureBuilder->build(
            $signatureBuilder->filterResponseParams($params, $type), SignatureBuilder::MODE_OUT);

        $signature = $signatureBuilder->hash($signature);

        if ($params['SHASIGN'] === $signature) {
            return true;
        }

        // mbe: @TODO Direkten Zugriff auf $_SERVER entfernen
        $this->getAdapter()->getLogger()->error('SECURE KEY INCORRECT - Received from IP: ' . $_SERVER['REMOTE_ADDR'], $params);
        return false;
    }

}

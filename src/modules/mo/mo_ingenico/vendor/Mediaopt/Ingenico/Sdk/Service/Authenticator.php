<?php

/**
 * $Id: Authenticator.php 53 2015-02-23 11:34:26Z mbe $ 
 */

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;
use Mediaopt\Ingenico\Sdk\Model\SHASettings;

/**
 * Authenticates so called SHAOut-Requests
 *  SHAOut => Response from Ingenico
 *  SHAIn  => Requests from Oxid to Ingenico
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
        /* @var $paramsModel IngenicoResponse */
        $paramsModel = $this->getAdapter()->getFactory('IngenicoResponse')->build(true);
        $params = $paramsModel->getAllParams();
        if (!$params['SHASIGN']) {
            $this->getAdapter()->getLogger()->error('SHA-Sign Parameter not present - please check Ingenico-Backend settings (technical-information' .
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

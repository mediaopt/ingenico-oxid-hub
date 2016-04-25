<?php

namespace Mediaopt\Ogone\Sdk\Service;

/**
 * $Id: SignatureBuilder.php 6 2015-02-23 15:50:57Z mbe $ 
 */
class SignatureBuilder extends AbstractService
{
    /**
     * @return array with filtered params and uppercased keys
     */
    public function filterResponseParams($params, $type = "")
    {
        $shaSettings = $this->getAdapter()->getFactory("SHASettings")->build();
        if ($type === "AliasGateway") {
            $shaOutParameters = $shaSettings->getSHAOutParametersAliasGateway();
        } elseif ($type === "HostedTokenizationPage") {
            $shaOutParameters = $shaSettings->getSHAOutParametersHostedTokenizationPage();
        } else {
            $shaOutParameters = $shaSettings->getSHAOutParameters();
        }
        
        // convert input param-keys to upercase
        $params = array_change_key_case($params, CASE_UPPER);

        // create an array with all params that should used for authentication
        $shaParams = array();

        // filter params
        foreach ($shaOutParameters as $paramName) {
            if (!isset($params[$paramName]) || $params[$paramName] === '') {
                continue;
            }
            $newParamName = str_replace("ALIAS_", "ALIAS.", $paramName);
            $newParamName = str_replace("CARD_", "CARD.", $newParamName);
            $shaParams[$newParamName] = $params[$paramName];
        }
        return $shaParams;
    }

    public function build($shaParams, $passPhrase)
    {
        if (empty($shaParams)) {
            return '';
        }

        // sort parameters alphabetically
        uksort($shaParams, "strnatcasecmp");

        // generate parameter signature before hashing
        $signature = '';
        foreach ($shaParams as $parameter => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }
            $signature .= strtoupper($parameter) . '=' . $value . $passPhrase;
        }

        return $signature;
    }

    public function hash($signature)
    {
        $shaSettings = $this->getAdapter()->getFactory("SHASettings")->build();
        $hashingAlgorithm = $shaSettings->getSHAAlgorithm();
        $algo = str_replace('-', '', strtolower($hashingAlgorithm));
        $result = strtoupper(hash($algo, $signature));
        return $result;
    }

}

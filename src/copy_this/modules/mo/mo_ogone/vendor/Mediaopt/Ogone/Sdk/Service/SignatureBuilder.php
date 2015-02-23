<?php

namespace Mediaopt\Ogone\Sdk\Service;

/**
 * $Id: SignatureBuilder.php 6 2015-02-23 15:50:57Z mbe $ 
 */
class SignatureBuilder extends AbstractService
{
    /* public function __construct($shaOutParameters, $hashingAlgorithm)
      {
      $this->shaOutParameters = $shaOutParameters;
      $this->hashingAlgorithm = $hashingAlgorithm;
      } */

    /**
     * @return array with filtered params and uppercased keys
     */
    public function filterResponseParams($params)
    {
        $shaSettings = $this->getAdapter()->getFactory("SHASettings")->build();
        $shaOutParameters = $shaSettings->getSHAOutParameters();

        // convert input param-keys to upercase
        $uppercasedParams = array();
        foreach ($params as $k => $v) {
            $uppercasedParams[strtoupper($k)] = $v;
        }

        // create an array with all params that should used for authentication
        $shaParams = array();

        // filter params
        foreach ($shaOutParameters as $paramName) {
            if (!isset($uppercasedParams[$paramName]) || $uppercasedParams[$paramName] === '') {
                continue;
            }
            $shaParams[strtoupper($paramName)] = $uppercasedParams[$paramName];
        }

        return $shaParams;
    }

    public function build($shaParams, $passPhrase)
    {
        if (empty($shaParams)) {
            return '';
        }

        // sort parameters alphabetically
        $shaParams = $this->keyNatSort($shaParams);

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

    /**
     * sort array keys naturally
     * 
     * @param type $array
     * @return type 
     */
    protected function keyNatSort($array)
    {
        $result = array();
        $keys = array_keys($array);

        natsort($keys);
        foreach ($keys as $key) {
            $result[$key] = $array[$key];
        }
        return $result;
    }

}

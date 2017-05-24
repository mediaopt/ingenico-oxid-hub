<?php

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Model\SHASettings;

/**
 * $Id: SignatureBuilder.php 6 2015-02-23 15:50:57Z mbe $
 */
class SignatureBuilder extends AbstractService
{

    const MODE_IN = 0;
    const MODE_OUT = 1;

    /**
     * @var SHASettings
     */
    protected $shaSettings;

    public function setShaSettings(SHASettings $shaSettings)
    {
        $this->shaSettings = $shaSettings;
    }

    /**
     * @param $params
     * @param string $type
     * @return array with filtered params and uppercased keys
     */
    public function filterResponseParams($params, $type = "")
    {
        if ($type === 'AliasGateway') {
            $shaOutParameters = $this->shaSettings->getSHAOutParametersAliasGateway();
        } elseif ($type === 'HostedTokenizationPage') {
            $shaOutParameters = $this->shaSettings->getSHAOutParametersHostedTokenizationPage();
        } else {
            $shaOutParameters = $this->shaSettings->getSHAOutParameters();
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
            $newParamName = str_replace('ALIAS_', 'ALIAS.', $paramName);
            $newParamName = str_replace('CARD_', 'CARD.', $newParamName);
            $shaParams[$newParamName] = $params[$paramName];
        }
        return $shaParams;
    }

    /**
     * special handling for params SCO_CATEGORY and SCORING needed
     * psp sorting differs from strnatcasecmp
     *
     * @param array $shaParams
     * @param int $mode
     * @return string
     */
    public function build($shaParams, $mode)
    {
        if (empty($shaParams)) {
            return '';
        }

        $passPhrase = $mode === self::MODE_IN ? $this->shaSettings->getSHAInKey() : $this->shaSettings->getSHAOutKey();

        // sort parameters alphabetically
        uksort($shaParams, 'strnatcasecmp');

        $switchScoringParameter = false;

        if (array_key_exists('SCORING', $shaParams) && array_key_exists('SCO_CATEGORY', $shaParams)) {
            $switchScoringParameter = true;
        }

        // generate parameter signature before hashing
        $signature = '';
        foreach ($shaParams as $parameter => $value) {
            if ($value === '' || null === $value) {
                continue;
            }

            if ($switchScoringParameter && $parameter === 'SCORING') {
                $signature .= strtoupper('SCO_CATEGORY') . '=' . $shaParams['SCO_CATEGORY'] . $passPhrase;
                continue;

            }
            if ($switchScoringParameter && $parameter === 'SCO_CATEGORY') {
                $signature .= strtoupper('SCORING') . '=' . $shaParams['SCORING'] . $passPhrase;
                continue;
            }

            $signature .= strtoupper($parameter) . '=' . $value . $passPhrase;
        }

        return $signature;
    }

    public function hash($signature)
    {
        $hashingAlgorithm = $this->shaSettings->getSHAAlgorithm();
        $algo = str_replace('-', '', strtolower($hashingAlgorithm));
        return strtoupper(hash($algo, $signature));
    }

}

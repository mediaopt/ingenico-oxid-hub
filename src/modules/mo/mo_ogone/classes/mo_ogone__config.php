<?php

/**
 * holds supported currencies and languages
 * maps oxid values to ogone values
 *
 * @author derksen mediaopt GmbH
 */
class mo_ogone__config
{
    /**
     * mo_ogone__config constructor.
     *
     * @param $configFile
     * @param $logger
     */
    public function __construct($configFile, $logger)
    {
        $this->logger = $logger;

        if (file_exists($configFile)) {
            include $configFile;
        }
    }

    /**
     * @param $oxidLanguageCode
     * @return mixed
     */
    public function getLanguageCountryCodeForOxidLanguageCode($oxidLanguageCode)
    {
        if (!isset($this->oxidLangCodeToOgoneLanguageCountryCode[$oxidLanguageCode])) {
            $this->logger->error(
                "Not supported language code: '$oxidLanguageCode', supported codes are: " .
                implode(', ', array_keys($this->oxidLangCodeToOgoneLanguageCountryCode)));
        }
        return $this->oxidLangCodeToOgoneLanguageCountryCode[$oxidLanguageCode];
    }

    /**
     * @param $oxidCurrencyCode
     * @return mixed
     */
    public function getOgoneCurrencyCode($oxidCurrencyCode)
    {
        // just check if the code is supported
        if (!in_array($oxidCurrencyCode, $this->supportedCurrencies, false)) {
            $this->logger->error(
                "Not supported currency code: '$oxidCurrencyCode', supported codes are: " .
                implode(', ', $this->supportedCurrencies) . ', please ask Ogone for support.');
        }
        return $oxidCurrencyCode;
    }

}

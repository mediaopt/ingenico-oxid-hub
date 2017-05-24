<?php

/**
 * holds supported currencies and languages
 * maps oxid values to ingenico values
 *
 * @author derksen mediaopt GmbH
 */
class mo_ingenico__config
{
    /**
     * mo_ingenico__config constructor.
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
        if (!isset($this->oxidLangCodeToIngenicoLanguageCountryCode[$oxidLanguageCode])) {
            $this->logger->error(
                "Not supported language code: '$oxidLanguageCode', supported codes are: " .
                implode(', ', array_keys($this->oxidLangCodeToIngenicoLanguageCountryCode)));
        }
        return $this->oxidLangCodeToIngenicoLanguageCountryCode[$oxidLanguageCode];
    }

    /**
     * @param $oxidCurrencyCode
     * @return mixed
     */
    public function getIngenicoCurrencyCode($oxidCurrencyCode)
    {
        // just check if the code is supported
        if (!in_array($oxidCurrencyCode, $this->supportedCurrencies, false)) {
            $this->logger->error(
                "Not supported currency code: '$oxidCurrencyCode', supported codes are: " .
                implode(', ', $this->supportedCurrencies) . ', please ask Ingenico for support.');
        }
        return $oxidCurrencyCode;
    }

}

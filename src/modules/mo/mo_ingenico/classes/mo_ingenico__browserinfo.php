<?php
/**
 * For the full copyright and license information, refer to the accompanying LICENSE file.
 *
 * @copyright 2019 Mediaopt GmbH
 */


/**
 * This class will provide data for making 3d secure Direct link possible
 *
 * @author Mediaopt GmbH
 */
class mo_ingenico__browserinfo
{

    /**
     * @var oxutilsserver
     */
    protected $utilsServer;

    public function __construct()
    {
        $this->utilsServer = oxRegistry::get('oxUtilsServer');
    }

    /**
     * @return string
     */
    public function getBrowserScreenWidth()
    {
        return (string)$this->getCookie('moIngenicoScreenWidth');
    }

    /**
     * @return string
     */
    public function getBrowserScreenHeight()
    {
        return (string)$this->getCookie('moIngenicoScreenHeight');
    }

    /**
     * @return string
     */
    public function getBrowserColorDepth()
    {
        return (string)$this->getCookie('moIngenicoColorDepth');
    }

    /**
     * @return string
     */
    public function getBrowserJavaEnabled()
    {
        return (string)$this->getCookie('moIngenicoJavaEnabled');
    }

    /**
     * @return string
     */
    public function getBrowserTimeZone()
    {
        return (string)$this->getCookie('moIngenicoTimeZone');
    }

    /**
     * @return string
     */
    public function getBrowserAcceptHeader()
    {
        return $this->getHeader('HTTP_ACCEPT');
    }

    /**
     * @return string
     */
    public function getBrowserUserAgent()
    {
        return $this->getHeader('HTTP_USER_AGENT');
    }

    /**
     * @return string
     */
    public function getBrowserLanguage()
    {
        $language = $this->getHeader('HTTP_ACCEPT_LANGUAGE');
        if ($pos = strpos($language, ',')) {
            $language = substr($language, 0, strpos($language, ','));
        }
        return $language;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getCookie($name)
    {
        return $this->utilsServer->getOxCookie($name);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getHeader($name)
    {
        return $this->utilsServer->getServerVar($name);
    }
}

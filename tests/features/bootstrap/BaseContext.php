<?php

use Facebook\WebDriver\Exception\ElementNotVisibleException;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Mediaopt\BehatRunner\BasicBehatContext;

/**
 * This class contains functions useful for writing tests
 */
class BaseContext extends BasicBehatContext
{
    /**
     * @Then each image in the body is displayed
     * @throws \Exception
     */
    public function eachImageInTheBodyDisplayed()
    {
        $brokenImages = [];
        $images = self::getWebDriver()
            ->findElements(WebDriverBy::xpath('//body//img'));
        foreach ($images as $image) {
            $imageSource = $image->getAttribute('src');
            if ($imageSource === '') {
                $brokenImages[] = 'Empty src tag - ' . $this->generateXPathExpressionToElement($image, '');
                continue;
            }
            $script = 'return typeof arguments[0].naturalWidth !== "undefined" && arguments[0].naturalWidth !== 0';
            $isLoaded = self::getWebDriver()->executeScript($script, [$image]);
            if (!$isLoaded) {
                $brokenImages[] = $imageSource;
            }
        }
        if ($brokenImages !== []) {
            $message = "\n" . implode("\n", $brokenImages);
            throw new \RuntimeException('Broken images found' . $message);
        }
    }

    /**
     * @param string $xPath
     * @throws \Exception
     */
    public function allLinksInElementAreShown($xPath)
    {
        $links = self::getWebDriver()
            ->findElements(WebDriverBy::xpath($xPath . '//a'));
        $invisibleLinks = [];
        foreach ($links as $link) {
            if (!$link->isDisplayed()) {
                $invisibleLinks[] = $link->getAttribute('href') . "\t\t" . $link->getText();
            }
        }
        if ($invisibleLinks !== []) {
            $message = implode("\n", $invisibleLinks);
            throw new \RuntimeException('Links are not displayed' . "\n" . $message);
        }
    }

    /**
     * @param string $xPath
     * @throws \Exception
     */
    public function allLinksInElementWork($xPath)
    {
        $linksElements = self::getWebDriver()
            ->findElements(WebDriverBy::xpath($xPath . '//a'));
        $links = [];
        //To avoid loosing elements during curl operations we add each link to the array
        foreach ($linksElements as $linksElement) {
            $links[] = ['href' => $linksElement->getAttribute('href'), 'text' => $linksElement->getText()];
        }
        $checkedLinks = [];
        $brokenLinks = [];
        foreach ($links as $link) {
            $href = $this->substituteDomain($link['href']);
            $text = $link['text'];
            if (array_key_exists($href, $checkedLinks)) {
                continue;
            }
            if (!$this->canUrlBeChecked($href)) {
                $checkedLinks[$href] = null;
                $brokenLinks[] = $href;
                continue;
            }
            if (!$this->isLinkReachable($href)) {
                $brokenLinks[] = $href . "\t\t" . $text;
                $checkedLinks[$href] = null;
                continue;
            }
            $checkedLinks[$href] = null;
        }
        if ($brokenLinks !== []) {
            $message = implode("\n", $brokenLinks);
            throw new \RuntimeException('Links are broken' . "\n" . $message);
        }
    }

    /**
     * if we test not production server we should replace link
     * to schuhcenter.de within environment variable $TEST_BASE_URL
     *
     * @param string $url
     * @return string
     */
    public function substituteDomain($url)
    {
        $returnUrl = $url;
        if (getenv('TEST_BASE_URL') !== 'http://www.schuhcenter.de') {
            preg_match('/^(http\:\/\/www\.schuhcenter.de)(.*)/', $url, $ref);
            if ($ref !== []) {
                $returnUrl = getenv('TEST_BASE_URL') . $ref[2];
            }
        }
        return $returnUrl;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function canUrlBeChecked($url)
    {
        return strpos('http', (string)$url) !== 0;
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function isLinkReachable($url)
    {
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlHandle, CURLOPT_HEADER, true);
        curl_setopt($curlHandle, CURLOPT_NOBODY, true);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 0);
        $response = curl_exec($curlHandle);
        curl_close($curlHandle);
        return $response;
    }

    /**
     * @param string $xPath
     * @throws \RuntimeException
     */
    public function theLinkInTheElementWithXpathWorks($xPath)
    {
        $href = self::getWebDriver()->findElement(WebDriverBy::xpath($xPath))->getAttribute('href');
        if (!$this->canUrlBeChecked($href)) {
            throw new \RuntimeException('Link could not be checked - ' . $href);
        }
        $hrefWithSubstitutedDomain = $this->substituteDomain($href);
        if (!$this->isLinkReachable($hrefWithSubstitutedDomain)) {
            throw new \RuntimeException('Link is broken - ' . $hrefWithSubstitutedDomain);
        }
    }
}

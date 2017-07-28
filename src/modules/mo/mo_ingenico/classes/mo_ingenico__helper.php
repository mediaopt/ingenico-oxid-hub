<?php

use Monolog\Handler\RotatingFileHandler;
use Psr\Log\LoggerInterface;

class mo_ingenico__helper
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function mo_ingenico__isBrandActive($pm, $brand)
    {
        $options = oxRegistry::getConfig()->getConfigParam('mo_ingenico__paymentOptions');

        if (null === $options) {
            return false;
        }

        $option = $options[$pm];
        return in_array($brand, $option, true);

    }

    /**
     * return last day of month
     * @param string $date
     * @return string
     */
    public function formatExpDate($date)
    {
        $year = substr($date, 2, 2);
        $month = substr($date, 0, 2);

        $dateTime = new DateTime( '20' . $year . '-' . $month . '-01' );

        return $dateTime->format('Y-m-t');
    }

    /**
     * return order amount of basket
     * @param oxBasket $oxBasket
     * @return string
     */
    public function getFormattedOrderAmount(oxBasket $oxBasket)
    {
        $dAmount = $oxBasket->getPrice()->getBruttoPrice();
        $dAmount = number_format($dAmount, 2, '.', '');
        $dAmount *= 100;
        $dAmount = round($dAmount, 0);
        $dAmount = substr($dAmount, 0, 15);
        return $dAmount;
    }

    /**
     * get logger
     * @return \Psr\Log\LoggerInterface;
     * @throws \InvalidArgumentException
     */
    public function getLogger()
    {
        if ($this->logger !== null) {
            return $this->logger;
        }
        $logger = new Monolog\Logger('mo_ingenico');
        $path = oxRegistry::get('oxConfig')->getLogsDir() . 'mo_ingenico';
        $formatter = new Monolog\Formatter\LineFormatter();
        $streamHandler = new RotatingFileHandler($path, 12, oxRegistry::get('oxConfig')->getShopConfVar('mo_ingenico__logLevel'));
        $streamHandler->setFilenameFormat('{filename}-{date}.log', 'Y-m-d');
        $streamHandler->setFormatter($formatter);
        $streamHandler->pushProcessor(new Monolog\Processor\IntrospectionProcessor());
        $streamHandler->pushProcessor(oxNew('mo_ingenico__monolog_processor'));
        $logger->pushHandler($streamHandler);
        return $this->logger = $logger;
    }

    /**
     * build log file path
     * @return string
     */
    public function getLogFilePath()
    {
        $files = glob(oxRegistry::getConfig()->getLogsDir() . 'mo_ingenico-*');
        sort($files, SORT_STRING);
        return end($files);
    }

    /**
     * Check if specified id is a payId or transactionId/orderId
     * e.g. orderId: mo_ingenico_591463d5161e45.44622883
     * e.g. payId: 3019930758
     * @param string $id
     * @return bool
     */
    public function isPayId($id)
    {
        return strpos($id, 'mo_ingenico_') === false;
    }
}
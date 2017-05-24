<?php

use Monolog\Handler\StreamHandler;
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
            //update processors
            return $this->logger;
        }
        $logger = new Monolog\Logger('mo_ingenico');
        $logFile = $this->getLogFilePath();
        $formatter = new Monolog\Formatter\LineFormatter();
        $streamHandler = new StreamHandler($logFile, oxRegistry::get('oxConfig')->getShopConfVar('mo_ingenico__logLevel'));
        $streamHandler->setFormatter($formatter);
        $streamHandler->pushProcessor(new Monolog\Processor\IntrospectionProcessor());
        $streamHandler->pushProcessor(oxNew('mo_ingenico__monolog_processor'));
        $fingersCrossedStreamHandler = new StreamHandler($this->getFingersCrossedLogFilePath(), oxRegistry::get('oxConfig')->getShopConfVar('mo_ingenico__logLevel'));
        $fingersCrossedStreamHandler->setFormatter($formatter);
        $fingersCrossedStreamHandler->pushProcessor(oxNew('mo_ingenico__monolog_processor'));
        $fingersCrossedHandler = new Monolog\Handler\FingersCrossedHandler($fingersCrossedStreamHandler);
        $logger->pushHandler($streamHandler);
        $logger->pushHandler($fingersCrossedHandler);
        return $this->logger = $logger;
    }

    /**
     * build log file path
     * @return string
     */
    public function getLogFilePath()
    {
        return oxRegistry::get('oxConfig')->getLogsDir() . 'mo_ingenico-' . date('Y-m', time()) . '.log';
    }


    /**
     * build fingerscrossed log file path
     * @return string
     */
    public function getFingersCrossedLogFilePath()
    {
        return oxRegistry::get('oxConfig')->getLogsDir() . 'mo_ingenico-fingerscrossed-' . date('Y-m', time()) . '.log';
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
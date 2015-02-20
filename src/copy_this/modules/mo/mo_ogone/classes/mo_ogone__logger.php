<?php

/**
 * $Id: mo_ogone__logger.php 6 2012-12-12 10:16:57Z martin $ 
 */
class mo_ogone__logger
{

  public function setConfig($config)
  {
    $this->config = $config;
  }

  public function logExecution($message = '')
  {
    if (!$this->isLogLevelActive('CALL'))
      return;

    if (!is_string($message))
    {
      $message = var_export($message, true);
    }
    if (!empty($message))
    {
      $message   = ' ' . $message;
    }
    $backtrace = debug_backtrace();
    $caller    = $backtrace[1];
    extract($caller);
    $this->message('CALL', "{$class}->{$function}$message ({$backtrace[0]['file']}:{$backtrace[0]['line']})");
  }

  public function info($message)
  {
    if (!$this->isLogLevelActive('INFO'))
      return;

    $this->message('INFO', $message);
  }

  public function error($message, $infoObject = null)
  {
    if (!$this->isLogLevelActive('ERROR'))
      return;

    if ($infoObject)
    {
      $message .= "\n" . print_r($infoObject, true) . "\n" . str_repeat('-', 80) . "\n";
    }
    $this->message('ERROR', $message);
  }

  public function message($type, $message)
  {
    if (!$this->isLogLevelActive($type))
      return;

    $logFile = oxRegistry::getConfig()->getLogsDir() . 'mo_ogone.log';
    $date    = date("Y-m-d H:i:s", time());

    $maxSize = $this->config->maximumLogfileSizeInBytes;
    if (filesize($logFile) > $maxSize)
    {
      file_put_contents($logFile, "$date DELETED LOGFILE (was greater than $maxSize bytes) \n");
    }

    $fLog = fopen($logFile, "a");
    if ($fLog)
    {
      fwrite($fLog, "$date $type: $message \n");
      fclose($fLog);
    }
  }

  protected function isLogLevelActive($level)
  {
    if (in_array('ALL', $this->config->logLevels))
    {
      return true;
    }
    if (in_array($level, $this->config->logLevels))
    {
      return true;
    }
    return false;
  }

}
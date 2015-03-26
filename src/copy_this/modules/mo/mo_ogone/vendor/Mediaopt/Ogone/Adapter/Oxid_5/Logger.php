<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5;

use Monolog;

class Logger extends Monolog\Logger
{

  public function logExecution($message = '')
  {
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
    parent::info("{$class}->{$function}$message ({$backtrace[0]['file']}:{$backtrace[0]['line']})");
  }

}
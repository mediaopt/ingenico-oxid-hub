<?php

/**
 * User: martinb
 * Date: 19.01.17
 * Time: 16:36
 */
class mo_ogone__logger extends Monolog\Logger
{

    public function logExecution($message = '')
    {
        if (!is_string($message)) {
            $message = var_export($message, true);
        }
        if (!empty($message)) {
            $message = ' ' . $message;
        }
        $backtrace = debug_backtrace();
        $caller = $backtrace[1];
        extract($caller, EXTR_OVERWRITE);
        parent::info("{$class}->{$function}$message ({$backtrace[0]['file']}:{$backtrace[0]['line']})");
    }

}
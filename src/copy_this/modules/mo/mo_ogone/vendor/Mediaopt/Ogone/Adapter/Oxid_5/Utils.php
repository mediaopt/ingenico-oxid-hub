<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5;

/**
 * $Id: mo_ogone__util.php 7 2012-12-12 10:44:58Z martin $ 
 */
class Utils
{

  static public function storeTransactionFeedbackInDb($requestParams, $orderid = "")
  {
    $sanitizedParams = array();
    //lowercase keys
    foreach ($requestParams as $key => $value)
    {
      $sanitizedParams[strtolower($key)] = $value;
    }

    $requestParamsOfInterest = array(
        //'orderid',
        'amount',
        'currency',
        'language',
        'pm',
        'brand',
        'cardno',
        'alias',
        'cn',
        'ed',
        'acceptance',
        'status',
        'trxdate',
        'ncerror',
        'ncerrorplus',
        'ncstatus',
        'cvccheck',
        'aavcheck',
        'eci',
        'vc',
        'scoring',
        'sco_category',
        'payid',
        'payidsub',
        'ip',
        'ipcty',
        'cccty',
    );

    $assignVariables = array(
        'date'      => date('Y-m-d H:i:s', time()),
    );

    foreach ($requestParamsOfInterest as $paramName)
    {
      if (!isset($sanitizedParams[$paramName]))
      {
        continue;
      }
      $assignVariables[$paramName] = $sanitizedParams[$paramName];
    }
    $assignVariables['billfname'] = $sanitizedParams['cn'];
    $assignVariables['transID'] = $sanitizedParams['orderid'];
    $assignVariables['orderID'] = $orderid;
    $log = oxNew('mo_ogone__payment_log');
    $log->assign($assignVariables);
    $log->save();
  }

}
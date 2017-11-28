<?php

class mo_ingenico__transaction_logger
{

    /**
     * @param array $requestParams
     * @param string $orderId
     */
    static public function storeTransaction($requestParams, $orderId = "")
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
            'shopid',
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
        $assignVariables['orderID'] = $orderId;
        $log = oxNew('mo_ingenico__payment_log');
        $log->assign($assignVariables);
        $log->save();
    }
}
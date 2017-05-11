<?php

class mo_ogone__aftersale_param_builder extends mo_ogone__request_param_builder
{

    /**
     * generate asset url
     */
    public function getUrl()
    {
        $part1 = 'test';
        if ($this->getOxConfig()->getShopConfVar('mo_ogone__isLiveMode')) {
            $part1 = 'prod';
        }

        return 'https://secure.ogone.com/ncol/' . $part1 . '/maintenancedirect.asp';
    }

    public function build(oxorder $order, $positionIds, $operation, $includeShipment = false, $includeGiftcard = false, $customAmount = false)
    {
        $gatewayParams = array();

        //calculateAmount
        $amount = !empty($customAmount) ? $customAmount : $this->getCaptureAmount($order, $positionIds, $includeShipment, $includeGiftcard);
        $gatewayParams['Amount'] = $amount;
        $gatewayParams['Currency'] = $order->getOrderCurrency()->name;
        $gatewayParams['Operation'] = $operation;
        if (oxNew('mo_ogone__helper')->isPayId($order->oxorder__oxtransid->value)) {
            $gatewayParams['Payid'] = $order->oxorder__oxtransid->value;
        } else {
            $gatewayParams['Orderid'] = $order->oxorder__oxtransid->value;
        }
        $gatewayParams['Pspid'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');;
        $gatewayParams['userid'] = $this->getOxConfig()->getConfigParam('mo_ogone__api_userid');
        $gatewayParams['pswd'] = $this->getOxConfig()->getConfigParam('mo_ogone__api_userpass');
        $gatewayParams['SHASIGN'] = $this->getShaSignForParams($gatewayParams);

        /* @var $model Mediaopt\Ogone\Sdk\Model\RequestParameters */
        $model = new \Mediaopt\Ogone\Sdk\Model\RequestParameters();
        $model->setParams($gatewayParams);
        $this->getLogger()->info('AfterSales params built', $model->getParams());
        return $model;
    }

    /**
     * calculate Capture Amount
     * @param oxOrder $order
     * @param array $positionIds
     * @param boolean $includeShipment
     * @param boolean $includeGiftcard
     * @return string
     */
    protected function getCaptureAmount(oxOrder $order, array $positionIds, $includeShipment, $includeGiftcard)
    {
        $amount = 0;
        foreach ($order->getOrderArticles() as $position) {
            /** @var oxOrderArticle $position */
            if (!in_array($position->getId(), $positionIds)) {
                continue;
            }
            // @TODO alreadyCaptured?
            //add difference between total price and already captured amount

            $amount += $position->oxorderarticles__oxbprice->value * $position->oxorderarticles__oxamount->value;
            if ($wrapping = $position->getWrapping()) {
                $amount += $wrapping->getWrappingPrice($position->oxorderarticles__oxamount->value)->getBruttoPrice();
            }
        }
        if ($includeGiftcard && ($giftcard = $order->getGiftCard())) {
            $amount += $giftcard->getWrappingPrice()->getBruttoPrice();
        }
        if ($includeShipment){
            $amount += $order->getOrderDeliveryPrice()->getBruttoPrice();
        }
        //sending amount * 100 to ingenico
        return bcmul($amount , 100);
    }

}
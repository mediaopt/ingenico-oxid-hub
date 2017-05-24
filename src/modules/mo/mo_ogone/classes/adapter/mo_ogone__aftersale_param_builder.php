<?php

class mo_ogone__aftersale_param_builder extends mo_ogone__request_param_builder
{

    /**
     * generate asset url
     */
    public function getUrl()
    {
        $part1 = $this->getOxConfig()->getShopConfVar('mo_ogone__isLiveMode')?'prod':'test';
        return 'https://secure.ogone.com/ncol/' . $part1 . '/maintenancedirect.asp';
    }

    /**
     * Build parameters for an aftersales operation. This can be capture or refund.
     * The amount to handle depends on the positionIds of the order and whether to handle shipmentcosts and giftcards
     * @param oxOrder $order
     * @param array $positionIds
     * @param string $operation
     * @param bool $includeShipment
     * @param bool $includeGiftcard
     * @return \Mediaopt\Ogone\Sdk\Model\RequestParameters
     */
    public function build(oxorder $order, $positionIds, $operation, $includeShipment = false, $includeGiftcard = false)
    {
        $gatewayParams = array();

        $gatewayParams['Amount'] = $this->getCaptureAmount($order, $positionIds, $includeShipment, $includeGiftcard);
        $gatewayParams['Currency'] = $order->getOrderCurrency()->name;
        $gatewayParams['Operation'] = $operation;
        if (oxNew('mo_ogone__helper')->isPayId($order->oxorder__oxtransid->value)) {
            $gatewayParams['Payid'] = $order->oxorder__oxtransid->value;
        } else {
            $gatewayParams['Orderid'] = $order->oxorder__oxtransid->value;
        }
        $gatewayParams['Pspid'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');
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
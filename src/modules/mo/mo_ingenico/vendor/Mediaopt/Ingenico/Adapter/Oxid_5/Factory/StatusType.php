<?php

namespace Mediaopt\Ingenico\Adapter\Oxid_5\Factory;


/**
 * $Id: $
 */
class StatusType extends AbstractFactory
{

    /**
     * populate StatusType model
     * @param $code
     * @return StatusType
     */
    public function build($code)
    {
        /* @var $model \Mediaopt\Ingenico\Sdk\Model\StatusType */
        $model = $this->getSdkMain()->getModel('StatusType');
        switch ($code) {
            case \Mediaopt\Ingenico\Sdk\Model\StatusType::ORDER_STORED:            // 4
            case \Mediaopt\Ingenico\Sdk\Model\StatusType::PAYMENT_REQUESTED:       // 9
                $model->setIsOkStatus(true);
                $model->setIsThankyouStatus(true);
                break;
            case \Mediaopt\Ingenico\Sdk\Model\StatusType::WAITING_CLIENT_PAYMENT:  // 41
            case \Mediaopt\Ingenico\Sdk\Model\StatusType::AUTHORIZED:              // 5
            case \Mediaopt\Ingenico\Sdk\Model\StatusType::AUTHORIZATION_WAITING:   // 51
            case \Mediaopt\Ingenico\Sdk\Model\StatusType::AUTHORIZATION_NOT_KNOWN: // 52
            case \Mediaopt\Ingenico\Sdk\Model\StatusType::PAYMENT_PROCESSING:      // 91
            case \Mediaopt\Ingenico\Sdk\Model\StatusType::PAYMENT_UNCERTAIN:       // 92
                $model->setIsThankyouStatus(true);
                $model->setIsOkStatus(false);
                break;
            default :
                $model->setIsThankyouStatus(false);
                $model->setIsOkStatus(false);
        }
        // create translated Status Message
        $paddedStatus = str_pad($code, 3, '0', STR_PAD_LEFT);
        $model->setTranslatedStatusMessage(
                $this->getOxLang()->translateString(
                    'MO_INGENICO__PAYMENT_STATUS_' . $paddedStatus, null, false)
        );
        return $model;
    }

}

<?php
/**
 * User: martinb
 * Date: 08.05.17
 * Time: 08:55
 */

namespace Mediaopt\Ingenico\Sdk\Service;

use Mediaopt\Ingenico\Sdk\Main;
use Mediaopt\Ingenico\Sdk\Model\IngenicoResponse;

class DirectGateway extends AbstractService
{
    protected $service = 'Direct';

    /**
     * set the type of service (e.g. Aftersales, OrderDirect) for logging
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->service = $type;
        return $this;
    }

    /**
     * convert the xml response into an IngenicoResponse object if data is present
     * create an IngenicoResponse with status incomplete or invalid otherwise
     * contains no sha-return because of server-to-server communication
     * @param $xml
     * @return IngenicoResponse
     */
    public function handleResponse($xml)
    {
        if (empty($xml)) {
            $this->getAdapter()->getLogger()->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
            /* @var $response \Mediaopt\Ingenico\Sdk\Model\IngenicoResponse */
            $response = Main::getInstance()->getModel('IngenicoResponse');
            return $response->markAsIncomplete();
        }
        //convert to array
        $response = $this->getResponse($xml);
        $this->getAdapter()->getLogger()->info('Handling ' . $this->service . ' Response', $response->getAllParams());
        if (!$response->hasError() && $this->checkForMandatoryFields($response)) {
            $this->getAdapter()->getLogger()->error('Mandatory fields missing!', $response->getAllParams());
            return $response->markAsIncomplete();
        }

        $this->logResponse($response);

        return $response;
    }

    /**
     *
     * @param $xml
     * @return \Mediaopt\Ingenico\Sdk\Model\IngenicoResponse
     */
    public function getResponse($xml)
    {
        $data = array();
        foreach ($xml->attributes() as $key => $value) {
            $data[strtoupper($key)] = (string) $value;
        }
        return $this->getAdapter()->getFactory('IngenicoResponse')->buildFromData($data);
    }

    /**
     * log the response as a success or failure
     * @param IngenicoResponse $response
     */
    protected function logResponse(IngenicoResponse $response)
    {
        $statusDebugInfo = 'Ingenico-Status: ' .
            $response->getStatus()->getStatusTextForCode() . ' (' . $response->getStatus()->getStatusCode() . ')';

        if ($response->getStatus()->isThankyouStatus()) {
            $this->getAdapter()->getLogger()->info('Ingenico Transaction Success - ' . $statusDebugInfo);
            return;
        }

        if ($response->hasError()) {
            $errorMessage = $response->getError()->getTranslatedStatusMessage();
        } else {
            $errorMessage = $response->getStatus()->getTranslatedStatusMessage();
        }

        $this->getAdapter()->getLogger()->info('Ingenico Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);
    }

    /**
     * check if the mandatory fields amount, status, payId and orderId are set
     *
     * @param IngenicoResponse $response
     *
     * @return bool
     */
    protected function checkForMandatoryFields(IngenicoResponse $response)
    {
        return null === $response->getAmount()
            || null === $response->getStatus()
            || null === $response->getPayId()
            || null === $response->getOrderId();
    }
}
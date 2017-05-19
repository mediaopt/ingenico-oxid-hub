<?php
/**
 * User: martinb
 * Date: 08.05.17
 * Time: 08:55
 */

namespace Mediaopt\Ogone\Sdk\Service;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\OgoneResponse;
use Mediaopt\Ogone\Sdk\Model\StatusType;

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
     * convert the xml response into an OgoneResponse object if data is present
     * create an OgoneResponse with status incomplete or invalid otherwise
     * contains no sha-return because of server-to-server communication
     * @param $xml
     * @return OgoneResponse
     */
    public function handleResponse($xml)
    {
        /* @var $response \Mediaopt\Ogone\Sdk\Model\OgoneResponse */
        $response = Main::getInstance()->getModel('OgoneResponse');
        if (empty($xml)) {
            $this->getAdapter()->getLogger()->error('Curl error detected, no direct link feedback! (we assume an uncertain status, redirect to error view)');
            $status = Main::getInstance()->getService('Status')
                ->usingStatusCode((int) StatusType::INCOMPLETE_OR_INVALID);
            $response->setStatus($status);
            return $response;
        }
        //convert to array
        $response = $this->getResponse($xml);
        $this->getAdapter()->getLogger()->info('Handling ' . $this->service . ' Response', $response->getAllParams());

        $this->logResponse($response);

        return $response;
    }

    /**
     *
     * @param $xml
     * @return \Mediaopt\Ogone\Sdk\Model\OgoneResponse
     */
    public function getResponse($xml)
    {
        $data = array();
        foreach ($xml->attributes() as $key => $value) {
            $data[strtoupper($key)] = (string) $value;
        }
        return $this->getAdapter()->getFactory('OgoneResponse')->buildFromData($data);
    }

    /**
     * log the response as a success or failure
     * @param OgoneResponse $response
     */
    protected function logResponse(OgoneResponse $response)
    {
        $statusDebugInfo = 'Ogone-Status: ' .
            $response->getStatus()->getStatusTextForCode() . ' (' . $response->getStatus()->getStatusCode() . ')';

        if ($response->getStatus()->isThankyouStatus()) {
            $this->getAdapter()->getLogger()->info('Ogone Transaction Success - ' . $statusDebugInfo);
            return;
        }

        if ($response->hasError()) {
            $errorMessage = $response->getError()->getTranslatedStatusMessage();
        } else {
            $errorMessage = $response->getStatus()->getTranslatedStatusMessage();
        }

        $this->getAdapter()->getLogger()->info('Ogone Transaction Failure: ' . $errorMessage . ' - ' . $statusDebugInfo);
    }

}
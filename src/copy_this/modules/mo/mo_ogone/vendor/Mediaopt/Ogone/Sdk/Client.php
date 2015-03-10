<?php

namespace Mediaopt\Ogone\Sdk;

class Client
{
    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
        public function call($url, $params)
    {
        if (!function_exists('curl_init')) {
            $message = 'curl is missing. Please install php curl extension.';
            $this->logger->error($message);
            return false;
        }
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
        ));

        $result = curl_exec($ch);

        // check for curl error
        if ($result === false) {
            // curl error
            $errorNumber = curl_errno($ch);
            $errorMessage = curl_error($ch);

            $this->logger->error("Curl error: $errorMessage ($errorNumber)");
        }

        curl_close($ch);

        return $result;
    }
    
}

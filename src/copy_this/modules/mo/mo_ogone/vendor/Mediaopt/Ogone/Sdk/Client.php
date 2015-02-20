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
    
}

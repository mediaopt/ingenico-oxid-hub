<?php

namespace Mediaopt\Ogone\Sdk\Model;

class Url extends AbstractModel
{
    /**
     *
     * @var string
     */
    public $url;
    
    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
}

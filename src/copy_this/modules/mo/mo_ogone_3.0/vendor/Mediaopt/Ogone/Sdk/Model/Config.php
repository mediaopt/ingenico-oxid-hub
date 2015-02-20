<?php

namespace Mediaopt\Ogone\Sdk\Model;

class Config extends AbstractModel
{
    /**
     *
     * @var boolean
     */
    public $isLiveMode;
    
    public function getIsLiveMode()
    {
        return $this->isLiveMode;
    }

    public function setIsLiveMode($isLiveMode)
    {
        $this->isLiveMode = $isLiveMode;
    }
}

<?php

class mo_ogone__helper
{

    public function mo_ogone__isBrandActive($pm, $brand)
    {
        $options = oxRegistry::getConfig()->getConfigParam('mo_ogone__paymentOptions');
        $option = $options[$pm];
        return in_array($brand, $option);
    }

}
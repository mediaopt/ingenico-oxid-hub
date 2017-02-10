<?php

class mo_ogone__helper
{

    public function mo_ogone__isBrandActive($pm, $brand)
    {
        $options = oxRegistry::getConfig()->getConfigParam('mo_ogone__paymentOptions');

        if (null === $options) {
            return false;
        }

        $option = $options[$pm];
        return in_array($brand, $option, true);

    }

}
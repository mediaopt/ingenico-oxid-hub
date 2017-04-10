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

    /**
     * return last day of month
     * @param string $date
     * @return string
     */
    public function formatExpDate($date)
    {
        $year = substr($date, 2, 2);
        $month = substr($date, 0, 2);

        $dateTime = new DateTime( '20' . $year . '-' . $month . '-01' );

        return $dateTime->format('Y-m-t');
    }
}
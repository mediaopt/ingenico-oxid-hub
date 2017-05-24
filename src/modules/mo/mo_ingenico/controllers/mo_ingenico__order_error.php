<?php

/**
 * $Id: mo_ingenico__order_error.php 53 2014-11-28 13:54:26Z martin $ 
 */
class mo_ingenico__order_error extends oxUBase
{

  protected $_sThisTemplate = 'order_error.tpl';

  public function mo_ingenico__getContent()
  {
    return sprintf(oxRegistry::getLang()->translateString('MO_INGENICO__ORDER_ERROR_CONTENT'), 
            $this->getConfig()->getRequestParameter('order_id'));
  }

}
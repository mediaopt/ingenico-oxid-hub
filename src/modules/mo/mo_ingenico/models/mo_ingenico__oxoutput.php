<?php

/**
 * $Id: mo_ingenico__oxoutput.php 27 2013-05-27 10:00:57Z martin $ 
 */
class mo_ingenico__oxoutput extends mo_ingenico__oxoutput_parent
{

  public function process($value, $viewClassName)
  {
    $parentResult = parent::process($value, $viewClassName);

    if ($viewClassName !== 'mo_ingenico__template')
    {
      return $parentResult;
    }

    // if shop runs in utf8 mode, ingenico needs the template still iso encoded,
    // but ingenico will output template as utf8, so the encoding info utf8 should inserted
    // in html header, alls chars not in iso-8859-1 should be html-entity-encoded.
    if (oxRegistry::getConfig()->getConfigParam('iUtfMode') && !oxRegistry::getConfig()->getShopConfVar('mo_ingenico__use_utf8'))
    {
      $parentResult = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $parentResult);
    }

    // remove base tag because the template is shown in ingenico-domain context and
    // ingenico will insert relative references to resources
    return $this->mo_ingenico__removeBaseTag($parentResult);
  }

  protected function mo_ingenico__removeBaseTag($output)
  {
    return preg_replace('%\<base[^>]+\>%', '', $output);
  }

}

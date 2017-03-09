<?php

/**
 * $Id: mo_ogone__oxoutput.php 27 2013-05-27 10:00:57Z martin $ 
 */
class mo_ogone__oxoutput extends mo_ogone__oxoutput_parent
{

  public function process($value, $viewClassName)
  {
    $parentResult = parent::process($value, $viewClassName);

    if ($viewClassName !== 'mo_ogone__template')
    {
      return $parentResult;
    }

    // if shop runs in utf8 mode, ogone needs the template still iso encoded,
    // but ogone will output template as utf8, so the encoding info utf8 should inserted
    // in html header, alls chars not in iso-8859-1 should be html-entity-encoded.
    if (oxRegistry::getConfig()->getConfigParam('iUtfMode') && !oxRegistry::getConfig()->getShopConfVar('mo_ogone__use_utf8'))
    {
      $parentResult = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $parentResult);
    }

    // remove base tag because the template is shown in ogone-domain context and
    // ogone will insert relative references to resources
    return $this->mo_ogone__removeBaseTag($parentResult);
  }

  protected function mo_ogone__removeBaseTag($output)
  {
    return preg_replace('%\<base[^>]+\>%', '', $output);
  }

}

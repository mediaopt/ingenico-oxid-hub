<?php

/**
 * $Id: function.mo_ogone__status_help.php 10 2012-12-12 16:49:30Z martin $ 
 */
function smarty_function_mo_ogone__status_help($params, &$smarty)
{
  $smarty->assign('sHelpId', 'mo_ogone_help_id_' . $params['code']);
  $smarty->assign('sHelpText', \Mediaopt\Ogone\Sdk\Main::getInstance()->getService("Status")->usingStatusCode($params['code'])->getTranslatedStatusMessage());
  return $smarty->fetch('inputhelp.tpl');
}
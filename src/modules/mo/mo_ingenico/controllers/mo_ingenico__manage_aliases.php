<?php

class mo_ingenico__manage_aliases extends Account
{

    /**
     * tpl file for payment form controller
     *
     * @var string
     */
    protected $_sThisTemplate = 'mo_ingenico__flow_manage_aliases.tpl';

    /**
     * tpl file for payment form controller azure style
     *
     * @deprecated will be removed with next major release
     * @var string
     */
    protected $_sThisTemplateAzure = 'mo_ingenico__manage_aliases.tpl';

  public function render()
  {
    if (!$this->mo_ingenico__isValidCall())
    {
      error_404_handler();
      exit;
    }
    
    // call parent render method
    parent::render();

    if($this->getViewConfig()->getActiveTheme() === 'azure') {
          return $this->_sThisTemplateAzure;
    }
    return $this->_sThisTemplate;
  }
  
  protected function mo_ingenico__isValidCall()
  {
    if (!$user = $this->getUser())
    {
      return false;
    }
    
    if(!$this->getConfig()->getShopConfVar('mo_ingenico__use_alias_manager'))
    {
      return false;
    }
    
    return true;
  }

  /**
   * @fnc 
   */
  public function mo_ingenico__delete()
  {
    $oxConfig = $this->getConfig();
    $oxUser   = $this->getUser();

    if ($card = $oxUser->mo_ingenico__getCard($oxConfig->getRequestParameter('id')))
    {
      $logger = oxNew('mo_ingenico__helper')->getLogger();
      $logger->info('Alias deleted',['user'=> $oxUser->oxuser__oxusername->value, 'alias'=> $card->mo_ingenico__alias__alias->value]);
      $card->delete();
      $oxUser->mo_ingenico__resetCards();
    }
  }

  /**
   * @fnc 
   */
  public function mo_ingenico__setDefault()
  {
    $oxConfig = $this->getConfig();
    $oxUser   = $this->getUser();

    if ($card = $this->getUser()->mo_ingenico__getCard($oxConfig->getRequestParameter('id')))
    {
        $logger = oxNew('mo_ingenico__helper')->getLogger();
        $logger->info('Alias set as default',['user'=> $oxUser->oxuser__oxusername->value, 'alias'=> $card->mo_ingenico__alias__alias->value]);
        $oxUser->mo_ingenico__setDefaultCard($card->getId());
    }
  }
}

<?php

class mo_ogone__manage_aliases extends Account
{

    /**
     * tpl file for payment form controller
     *
     * @var string
     */
    protected $_sThisTemplate = 'mo_ogone__flow_manage_aliases.tpl';

    /**
     * tpl file for payment form controller azure style
     *
     * @deprecated will be removed with next major release
     * @var string
     */
    protected $_sThisTemplateAzure = 'mo_ogone__manage_aliases.tpl';

  public function render()
  {
    if (!$this->mo_ogone__isValidCall())
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
  
  protected function mo_ogone__isValidCall()
  {
    if (!$user = $this->getUser())
    {
      return false;
    }
    
    if(!$this->getConfig()->getShopConfVar('mo_ogone__use_alias_manager'))
    {
      return false;
    }
    
    return true;
  }

  /**
   * @fnc 
   */
  public function mo_ogone__delete()
  {
    $oxConfig = $this->getConfig();
    $oxUser   = $this->getUser();

    if ($card = $oxUser->mo_ogone__getCard($oxConfig->getRequestParameter('id')))
    {
      $card->delete();
      $oxUser->mo_ogone__resetCards();
    }
  }

  /**
   * @fnc 
   */
  public function mo_ogone__setDefault()
  {
    $oxConfig = $this->getConfig();
    $oxUser   = $this->getUser();

    if ($card = $this->getUser()->mo_ogone__getCard($oxConfig->getRequestParameter('id')))
    {
      $oxUser->mo_ogone__setDefaultCard($card->getId());
    }
  }
}

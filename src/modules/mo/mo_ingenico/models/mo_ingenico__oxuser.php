<?php

/**
 * $Id: $
 */
class mo_ingenico__oxuser extends mo_ingenico__oxuser_parent
{
  protected $mo_ingenico__aliases = null;

    /**
     * @return oxList
     */
  public function mo_ingenico__getCards()
  {
    if($this->mo_ingenico__aliases !== null)
    {
      return $this->mo_ingenico__aliases;
    }
    
    $aliasList = oxNew('oxlist', 'mo_ingenico__alias');
    $alias = oxNew('mo_ingenico__alias');
    $oxDb = oxDb::getDb();
    
    $sql = "SELECT *
            FROM " . $alias->getViewName() . "
            WHERE 
            OXUSERID = " . $oxDb->quote($this->getId())  . " AND
            EXP_DATE >= " . $oxDb->quote(date('Y-m-d', time())) . "
            ORDER BY IS_DEFAULT DESC, EXP_DATE ASC";
    
    $aliasList->selectString($sql);
    return $this->mo_ingenico__aliases = $aliasList;
  } 
         
  public function mo_ingenico__registerAlias(array $requestParams)
  {
      $logger = oxNew('mo_ingenico__helper')->getLogger();
      $logger->info('Register alias', $requestParams);
      $sanitizedParams = array();
      //lowercase keys
      foreach ($requestParams as $key => $value)
      {
          $sanitizedParams[strtolower($key)] = $value;
      }

    //check if ppan is already registered to user
    if($this->mo_ingenico__getCardByAlias($sanitizedParams['alias']))
    {
        $logger->info('Alias already stored',['user'=> $this->oxuser__oxusername->value, 'alias'=> $sanitizedParams['alias']]);
        return true;
    }
    
    $expDate = oxNew('mo_ingenico__helper')->formatExpDate($sanitizedParams['ed']);
      $requestParamsOfInterest = array(
          'pm',
          'brand',
          'cardno',
          'alias',
          'cn',
      );

      $assignVariables = array(
          'created_at' => date('Y-m-d H:i:s', time()),
          'oxuserid'   => $this->getId(),
          'exp_date'   => $expDate,
          'is_default' => $this->mo_ingenico__getDefaultCard() === null?1:0,
      );

      foreach ($requestParamsOfInterest as $paramName)
      {
          if (!isset($sanitizedParams[$paramName]))
          {
              continue;
          }
          $assignVariables[$paramName] = $sanitizedParams[$paramName];
      }
      $alias = oxNew('mo_ingenico__alias');
      $alias->assign($assignVariables);
      $alias->save();
      $logger->info('Alias stored',['user'=> $this->oxuser__oxusername->value, 'alias'=> $assignVariables['alias']]);

    //reset stored cards
    $this->mo_ingenico__resetCards();

    return $this->save();
  }

  public function mo_ingenico__hasValidRegisteredAliasCard()
  {
    return count($this->mo_ingenico__getCards());
  }

  /**
   * return card by ID
   * @param type $id
   * @return mixed
   */
  public function mo_ingenico__getCard($id)
  {
    if(empty($id))
    {
      return null;
    }
    
    $cards = $this->mo_ingenico__getCards();
    return isset($cards[$id]) ? $cards[$id] : null;
  }
  
  public function mo_ingenico__getDefaultCard()
  {
    foreach($this->mo_ingenico__getCards() as $card)
    {
      if($card->mo_ingenico__alias__is_default->value)
      {
        return $card;
      }
    }
    
    return null;
  }
  
  public function mo_ingenico__setDefaultCard($cardId)
  {
    foreach($this->mo_ingenico__getCards() as $card)
    {
      $isDefault = $card->getId() == $cardId ? 1 : 0;
      $card->mo_ingenico__alias__is_default = new oxField($isDefault);
      $card->save();
    }
    
    //reset stored cards
    $this->mo_ingenico__resetCards();
  }
  
  public function mo_ingenico__getCardByAlias($alias)
  {
    foreach ($this->mo_ingenico__getCards() as $card)
    {
      if($card->mo_ingenico__alias__alias->value === $alias)
      {
        return $card;
      }
    }
    return null;
  }
  
  /**
   * force reload of cards
   */
  public function mo_ingenico__resetCards()
  {
    $this->mo_ingenico__aliases = null;
  }
}
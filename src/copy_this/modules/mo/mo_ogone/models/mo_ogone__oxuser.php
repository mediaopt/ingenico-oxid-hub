<?php

/**
 * $Id: $
 */
class mo_ogone__oxuser extends mo_ogone__oxuser_parent
{
  protected $mo_ogone__aliases = null;

    /**
     * @return oxList
     */
  public function mo_ogone__getCards()
  {
    if($this->mo_ogone__aliases !== null)
    {
      return $this->mo_ogone__aliases;
    }
    
    $aliasList = oxNew('oxlist', 'mo_ogone__alias');
    $alias = oxNew('mo_ogone__alias');
    $oxDb = oxDb::getDb();
    
    $sql = "SELECT *
            FROM " . $alias->getViewName() . "
            WHERE 
            OXUSERID = " . $oxDb->quote($this->getId())  . " AND
            EXP_DATE >= " . $oxDb->quote(date('Y-m-d', time())) . "
            ORDER BY IS_DEFAULT DESC, EXP_DATE ASC";
    
    $aliasList->selectString($sql);
    return $this->mo_ogone__aliases = $aliasList;
  } 
         
  public function mo_ogone__registerAlias(array $requestParams)
  {
      $logger = oxNew('mo_ogone__helper')->getLogger();
      $logger->info('Register alias', $requestParams);
      $sanitizedParams = array();
      //lowercase keys
      foreach ($requestParams as $key => $value)
      {
          $sanitizedParams[strtolower($key)] = $value;
      }

    //check if ppan is already registered to user
    if($this->mo_ogone__getCardByAlias($sanitizedParams['alias']))
    {
        $logger->info('Alias already stored',['user'=> $this->oxuser__oxusername->value, 'alias'=> $sanitizedParams['alias']]);
        return true;
    }
    
    $expDate = oxNew('mo_ogone__helper')->formatExpDate($sanitizedParams['ed']);
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
          'is_default' => $this->mo_ogone__getDefaultCard() === null?1:0,
      );

      foreach ($requestParamsOfInterest as $paramName)
      {
          if (!isset($sanitizedParams[$paramName]))
          {
              continue;
          }
          $assignVariables[$paramName] = $sanitizedParams[$paramName];
      }
      $alias = oxNew('mo_ogone__alias');
      $alias->assign($assignVariables);
      $alias->save();
      $logger->info('Alias stored',['user'=> $this->oxuser__oxusername->value, 'alias'=> $assignVariables['alias']]);

    //reset stored cards
    $this->mo_ogone__resetCards();

    return $this->save();
  }

  public function mo_ogone__hasValidRegisteredAliasCard()
  {
    return count($this->mo_ogone__getCards());
  }

  /**
   * return card by ID
   * @param type $id
   * @return mixed
   */
  public function mo_ogone__getCard($id)
  {
    if(empty($id))
    {
      return null;
    }
    
    $cards = $this->mo_ogone__getCards();
    return isset($cards[$id]) ? $cards[$id] : null;
  }
  
  public function mo_ogone__getDefaultCard()
  {
    foreach($this->mo_ogone__getCards() as $card)
    {
      if($card->mo_ogone__alias__is_default->value)
      {
        return $card;
      }
    }
    
    return null;
  }
  
  public function mo_ogone__setDefaultCard($cardId)
  {
    foreach($this->mo_ogone__getCards() as $card)
    {
      $isDefault = $card->getId() == $cardId ? 1 : 0;
      $card->mo_ogone__alias__is_default = new oxField($isDefault);
      $card->save();
    }
    
    //reset stored cards
    $this->mo_ogone__resetCards();
  }
  
  public function mo_ogone__getCardByAlias($alias)
  {
    foreach ($this->mo_ogone__getCards() as $card)
    {
      if($card->mo_ogone__alias__alias->value === $alias)
      {
        return $card;
      }
    }
    return null;
  }
  
  /**
   * force reload of cards
   */
  public function mo_ogone__resetCards()
  {
    $this->mo_ogone__aliases = null;
  }
}
<?php

/**
 * $Id: mo_ogone__oxbasketitem.php 6 2012-12-12 10:16:57Z martin $ 
 */

/**
 * @extend oxbasketitem 
 */
class mo_ogone__oxbasketitem extends mo_ogone__oxbasketitem_parent
{

    /**
     * prevent basketitem isBuyable validation
     * use of basket between order and thankyou views causes errors, when buying last item in stock
     *
     * @param bool $blCheckProduct
     * @param string $sProductId
     * @param bool $blDisableLazyLoading
     * @return parent call
     * @throws \oxArticleException
     */
  public function getArticle($blCheckProduct = true, $sProductId = null, $blDisableLazyLoading = false)
  {
    if (!oxRegistry::getConfig()->getConfigParam('mo_ogone__prevent_recalculate'))
    {
      return parent::getArticle($blCheckProduct, $sProductId, $blDisableLazyLoading);
    }

    return parent::getArticle(false, $sProductId, $blDisableLazyLoading);
  }

}

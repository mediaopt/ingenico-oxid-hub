<?php

/**
 * $Id: mo_ogone__deferred_feedback.php 7 2012-12-12 10:44:58Z martin $ 
 */
class mo_ogone__deferred_feedback extends oxUBase
{

  public function render()
  {
    mo_ogone__main::getInstance()->getFeedbackHandler()->processDeferredFeedback($_REQUEST);

    // offline request from ogone, no further processing needed
    exit;
  }

}
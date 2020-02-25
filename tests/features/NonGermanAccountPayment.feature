@NonGermanAccountPayment
Feature: Testing Ingenico Payments for non German accounts
  This Feature Test

  Scenario: the basket is empty initially
    When I access the main page
    And  I can see the basket symbol
    Then I can see that the basket is empty

  Scenario: Add item into basket
    Given I access the main page
    And I can see that the basket is empty
    When I click on add first displayed product into basket button
    And I Click On To Cart Button Modal Box
    Then I can see that the basket has product

  Scenario: Go to Address page of checkout from Product page
    Given I am on Shop Cart
    And I am loggedOut
    When I click on Next Button
    And I logIn
    Then I am on Address page of checkout

  Scenario: Go to check Payment page of checkout from Product page
    Given I am on Address page of checkout
    When I click on Next Button
    Then I am on payments page of checkout

  Scenario: Select payment and go to check page
    Given I am on payments page of checkout
    When I Choose "Direct Pay For Non German Account" Payment
    And I click on Next Button
    Then I can see the check and address page

  Scenario: Finish the order
    Given I am on order page of checkout
    When I click on the pay now button
    Then  I can see Ingenico Payments Simulator

  Scenario: Finish Direct payment form ingenico Zone With accepted
    And  I accept payment
    Then I can see the thank you message
    And I can see that the basket is empty

  Scenario: I am logging out
    Given I am loggedIn
    When I click on service menu
    And I click on log out
    Then I am loggedOut


@ActivatePayments
Feature:Activation Ingenico Payments
  This feature check existence Ingenico PlugIn on OXID
  & activate it

  Scenario: check if shop is online
    Given shop is online
    When I access the Admin Page
    Then I can see text "Master Settings" Into eShop Admin

  Scenario: check if ingenico in the modules list
    When I click on extensions collapse menu
    And I click modules settings collapse menu
    Then I can see modules activation list

  Scenario: If ingenico module deactivated, and activate it
    Given Ingenico Module is deactivated
    When I click on ingenico module
    And I active Ingenico
    Then Ingenico module is activated

  Scenario: Check & Setup API Settings
    When I access "settings" Tab Of Standard Shipping Method
    And I click on API Settings Collapse Menu
    And I check & setup API Setting "live mode"
    And I check & setup API Setting "credit card transact"
    And I check & setup API Setting "utf-8 encode"
    And I check & setup API Setting "set payment date"
    And I check & setup API Setting "hidden authorization"
    And I check & setup API Setting "iframe"
    And I check & setup API Setting "use alias manager"
    And I check & setup API Setting "oxtransid"
    And I check & setup API Setting "pspid"
    And I check & setup API Setting "user id"
    And I check & setup API Setting "user password"
    And I check & setup API Setting "hash algorithm"
    And I check & setup API Setting "sha in signature"
    And I check & setup API Setting "sha out signature"
    And I check & setup API Setting "time out"
    And I save API Settings

  Scenario: check Ingenico payments list
    When I click on ingencio collapse menu
    And I click ingenico settings collapse menu
    Then I can see Ingenico Payments list settings

  Scenario: Check & Active Ingenico Payment at shop
    When I active ingenico Payments
    And I click on update Payment
    Then I can see Ingenico Payments are selected

  Scenario: Check if Payments are activated in Payments Methods
    When I click on Shop settings collapse menu
    And I click on Payments Methods collapse menu
    Then I can see that Payments are activated

  Scenario: Check And Access Standard Shipping Methods
    When I click on Shop settings collapse menu
    And I click on Shipping Methods collapse menu
    And I choose Standard Payment type from Shipping payment list
    Then I am on Main Tab of Standard Shipping Method

  Scenario: Open And Assign Countries Standard Payment Methods
    Given I access "Main" Tab Of Standard Shipping Method
    When I click on Assign Countries button of Main Tab Of Standard Shipping Method
    And I access "Assign Countries" Dialog Box
    And I click on Assign All button
    Then I can see Assign Countries of the Assigned Countries right box
    And I close the popout box

  Scenario: Open and Assign Payments to Standard Payments Method
    Given I access "Payment" Tab Of Standard Shipping Method
    When I click on assign payment methods button
    And I access "Assign Payments" Dialog Box
    And I click on Assign All button
    Then I can see the payments on Assigned Payment Methods
    And I close the popout box

  Scenario: Open and Assign User Groups to Standard Shipping Method
    Given I access "Users" Tab Of Standard Shipping Method
    When I click on Assign User Group on main Tab of payment
    And I access "Assign User Group" Dialog Box
    And I click on Assign All button
    Then All User Group is assigned
    And I close the popout box
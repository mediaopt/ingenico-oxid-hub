<?php

use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\Interactions\WebDriverTouchActions;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;
use Mediaopt\BehatRunner\Environment;
use Facebook\WebDriver\WebDriverSelect;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\UnexpectedTagNameException;

/**
 * @author  Mediaopt GmbH
 */
class IngenicoContext extends BaseContext
{
    /**
     * @Given /^I can see the basket symbol$/
     * @throws Exception
     */
    public function iCanSeeTheBasketSymbol()
    {
        $this->iWaitForThePageToBeLoaded();
        $this->iCanTheSeeElement(Elements::BASKET_SYMBOL_CLASS);
    }

    /**
     * @Then /^I can see that the basket is empty$/
     */
    public function iCanSeeThatTheBasketIsEmpty()
    {
        WebDriverExpectedCondition::elementTextContains($this->getSelector(Elements::BASKET_SYMBOL_CONTENT), '0');
    }

    /**
     * @When /^I click on add first displayed product into basket button$/
     */
    public function iClickOnAddFirstDisplayedProductIntoBasketButton()
    {
        self::$driver->findElements($this->getSelector(Elements::PRODUCTS_BUTTONS))[0]
            ->click();
    }

    /**
     * @Given /^I Click On To Cart Button Modal Box$/
     */
    public function iClickOnToCartButtonInModalBox()
    {
        $this->getElement(Elements::BASKET_MODAL_BUTTON)->click();
    }

    /**
     * @Then /^I can see that the basket has product$/
     * @throws WebDriverException
     */
    public function iCanSeeThatTheBasketHasProduct()
    {
        $itemCount = count(self::$driver->findElements($this->getSelector(Elements::PRODUCTS_IN_BASKET)));
        if ($itemCount !== 1) {
            throw new WebDriverException("The number of product in basket is {$itemCount}");
        }
    }

    /**
     * @Given /^I am on Shop Cart$/
     */
    public function iAmOnShopCart()
    {
        WebDriverExpectedCondition::presenceOfElementLocated($this->getSelector(Elements::CHECKOUT_CART_PAGE));
    }

    /**
     * @Given /^I am loggedOut$/
     * @throws WebDriverException
     */
    public function iAmLoggedOut()
    {
        $this->iCanTheSeeElement(Elements::LOGIN_SHOW_STATUS_CLASS);
    }

    /**
     * @When /^I click on Next Button$/
     * @throws Exception
     */
    public function iClickOnNextButtonToAddressPage()
    {
        self::$driver->findElements($this->getSelector(Elements::CHECKOUT_NEXT_BUTTON))[0]->click();
        $this->iWaitForThePageToBeLoaded();
    }

    /**
     * @When /^I click on the basket symbol$/
     */
    public function iClickOnTheBasketSymbol()
    {
        $this->getElement('div.minibasket-menu button')->click();
    }

    /**
     * @Given /^I logIn$/
     * @throws Exception
     */
    public function iAmLoggingInWith()
    {
        $inputUserElement = $this->getElement(Elements::CHECKOUT_USER_NAME_INPUT);
        $inputPasswordElement = $this->getElement(Elements::CHECKOUT_USER_PASSWORD_INPUT);
        $inputUserElement->sendKeys(getenv(Environment::TEST_USER));
        $inputPasswordElement->sendKeys(getenv(Environment::TEST_PASSWORD));
        if ($this->isPlatformMobileDevice() || $this->isMobileWidth()) {
            $this->getElement(Elements::CHECKOUT_LOGIN_FORM)->submit();
        } else {
            $this->getElement(Elements::CHECKOUT_LOGIN_BUTTON)->click();
        }
        $this->iWaitForThePageToBeLoaded();
    }

    /**
     * @Then /^I am on Address page of checkout$/
     * @throws WebDriverException
     */
    public function iAmOnAddressPageOfCheckout()
    {
        $this->iCanTheSeeElement(Elements::CHECKOUT_ADDRESS_PAGE_ACTIVE);
    }

    /**
     * @Then /^I am on payments page of checkout$/
     * @throws WebDriverException
     */
    public function iAmOnPaymentsPageOfCheckout()
    {
        $this->iCanTheSeeElement(Elements::CHECKOUT_PAYMENTS_PAGE_ACTIVE);
    }

    /**
     * @When /^I Choose "([^"]*)" Payment$/
     * @param string $payment
     */
    public function iChooseCertainPayment($payment)
    {
        $payment = strtolower(str_replace(' ', Payments::KEYS_SEPARATOR, $payment));
        $selector = $this->buildSelectorFromTemplate(
            Elements::CHECKOUT_PAYMENTS_PAYMENT_INPUT_IDENTIFICATION,
            ['{$payment_value}' => Payments::ACTIVATE_PAYMENTS[$payment]]
        );
        $this->getElement($selector)->click();
    }

    /**
     * @Then /^I can see the check and address page$/
     * @throws Exception
     */
    public function iCanSeeTheCheckAndAddressPage()
    {
        $this->iWaitForThePageToBeLoaded();
        $this->iCanTheSeeElement(Elements::CHECKOUT_CHECK_PAGE_ACTIVE);
    }

    /**
     * @When /^I enter PayPal password$/
     */
    public function iEnterPayPalPassword()
    {
        $this->getElement('#password')->sendKeys(getenv(Environment::TEST_PASSWORD));
    }

    /**
     * @Given /^I click on the PayPal logIn button$/
     * @throws Exception
     */
    public function iClickOnThePayPalLogInButton()
    {
        $this->getElement('#btnLogin')->click();
        $this->iWaitForTheElementWithXpath(66, "//*[@id='confirmButtonTop']");
    }

    /**
     * @Given /^I click on the PayPal next button$/
     * @throws Exception
     */
    public function iClickOnThePayPalNextButton()
    {
        $this->iWaitForTheElementWithXpath(60, "//*[@id='confirmButtonTop']");
        $button = $this->getElement('#confirmButtonTop');
        $action = new WebDriverActions(self::$driver);
        $action->moveToElement($button)->click()->perform();
    }

    /**
     * @When /^ingecio: I click on pay button$/
     */
    public function iClickOnPayButton()
    {
        $this->getElement('form[id="orderConfirmAgbBottom"] button')->click();
    }

    /**
     * @Then /^I am on order page of checkout$/
     * @throws WebDriverException
     */
    public function iAmOnOrderPageOfCheckout()
    {
        $this->iCanTheSeeElement('//li[@class="step4 active "]');
    }

    /**
     * @Given /^I click on the pay now button$/
     */
    public function iClickOnThePayNowButton()
    {
        $this->getElement(Elements::CHECKOUT_PAY_BUTTON)->click();
    }

    /**
     * @Given /^I choose Direct Pay Simulator$/
     * @throws Exception
     */
    public function iChooseDirectPaySimulator()
    {
        $this->iWaitForTheElement(60, Elements::DIRECTPAY_SIMULATOR_INPUT);
        $this->getElement(Elements::DIRECTPAY_SIMULATOR_INPUT)->click();
    }

    /**
     * @Given /^I can see Ingenico Payments Simulator$/
     * @throws Exception
     */
    public function iCanSeeIngenicoPaymentsSimulator()
    {
        $this->iWaitForTheElement(60, Elements::SIMULATOR_ID);
    }

    /**
     * @When /^I accept payment$/
     */
    public function iAcceptPayment()
    {
        $this->getElement(Elements::SIMULATOR_ACCEPTANCE_BUTTON)->click();
    }

    /**
     * @Then /^I can see the thank you message$/
     * @throws Exception
     */
    public function iCanSeeTheThankYouMessage()
    {
        $this->iCanTheSeeElement(Elements::THANKS_MESSAGE_ID);
    }

    /**
     * @Then /^I can see the main page$/
     * @throws WebDriverException
     */
    public function iCanSeeTheMainPage()
    {
        $linkStartPage = $this->getElementByCss('link[rel="canonical"]')->getAttribute('href');
        if (parse_url($linkStartPage, PHP_URL_PATH) !== '/startseite/') {
            throw new WebDriverException('The order did not close correctly.');
        }
    }

    /**
     * @Given /^shop is online$/
     */
    public function shopIsOnline()
    {
        self::$driver->get($this->getBaseUrl());
        $action = new WebDriverActions(self::$driver);
        $action->sendKeys($this->getElement('body'), WebDriverKeys::TAB);
        WebDriverExpectedCondition::invisibilityOfElementLocated($this->getSelector('#main-frame-error'));
    }

    /**
     * @When /^I access the Admin Page$/
     */
    public function iAccessTheAdminPage()
    {
        $action = new WebDriverTouchActions(self::$driver);
        self::$driver->get($this->getBaseUrl() . '/admin');
        $this->getElement(Elements::ADMIN_LOGIN_USER_INPUT)->sendKeys(getenv(Environment::ADMIN_USER));
        $this->getElement(Elements::ADMIN_LOGIN_PASSWORD_INPUT)->sendKeys(getenv(Environment::ADMIN_PASSWORD));
        if ($this->isPlatformMobileDevice() || $this->isMobileWidth()) {
            $this->getElement(Elements::ADMIN_LOGIN_SUBMIT_FORM)->submit();
        } else {
            $this->getElement(Elements::ADMIN_LOGIN_SUBMIT_BUTTON)->click();
        }
    }

    /**
     * @Then /^I can see text "([^"]*)" Into eShop Admin$/
     * @param string $masterSettings
     */
    public function iCanSeeTextIntoEShopAdmin($masterSettings)
    {
        self::$driver->switchTo()->defaultContent()->switchTo()->frame(Elements::FRAME_MAIN_NAVIGATION)->switchTo()->frame(Elements::FRAME_LEFT_NAVIGATION);
        $adminSiteSymbol = self::$driver->findElement($this->getSelector('.main h2'));
        WebDriverExpectedCondition::visibilityOf($adminSiteSymbol);
    }

    /**
     * @When /^I click on extensions collapse menu$/
     */
    public function iClickOnExtensionsCollapseMenu()
    {
        $this->getElement(Elements::ADMIN_EXTENSION_NAVIGATION)->click();
    }

    /**
     * @Given /^I click modules settings collapse menu$/
     */
    public function iClickModulesSettingsCollapseMenu()
    {
        $this->getElement(Elements::ADMIN_MODELS_SETTINGS_NAVIGATION)->click();
    }

    /**
     * @Then /^I can see modules activation list$/
     */
    public function iCanSeeModulesActivationList()
    {
        WebDriverExpectedCondition::presenceOfElementLocated(
            $this->getSelector(Elements::ADMIN_MODULES_ACTIVATION_LIST)
        );
    }

    /**
     * @Given /^Ingenico Module is deactivated$/
     * @throws WebDriverException
     */
    public function ingeniousModuleIsDeactivated()
    {
        $listModulesFrame = self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_BASIC)
            ->switchTo()->frame(Elements::FRAME_LIST);
        $modules = $listModulesFrame->findElements($this->getSelector(Elements::ADMIN_MODULES_LIST_ITEMS));
        foreach ($modules as $module) {
            if (strtolower(trim($module->getText())) === 'ingenico') {
                throw new WebDriverException('Ingenico is already activated');
            }
        }
    }

    /**
     * @When /^I click on ingenico module$/
     */
    public function iClickOnIngenicoModule()
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_BASIC)
            ->switchTo()->frame(Elements::FRAME_LIST);
        $this->getElement(Elements::ADMIN_INGENICO_MODULE_LIST_ITEMS)->click();
    }

    /**
     * @Given /^I active Ingenico$/
     */
    public function iClickOnIngenicoActivationButton()
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_BASIC)
            ->switchTo()->frame(Elements::FRAME_EDIT);
        $this->getElement(Elements::ADMIN_INGENICO_MODULE_ACTIVATION_BUTTON)->click();
    }

    /**
     * @Then /^Ingenico module is activated$/
     */
    public function ingenicoModuleIsActivated()
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_BASIC)
            ->switchTo()->frame(Elements::FRAME_LIST);
        WebDriverExpectedCondition::visibilityOfElementLocated($this->getSelector(Elements::ADMIN_INGENICO_MODULE_STATUS_ACTIVE));
    }

    /**
     * @Given /^I click on API Settings Collapse Menu$/
     */
    public function iClickOnAPISettingsCollapseMenu()
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_BASIC)
            ->switchTo()->frame(Elements::FRAME_EDIT);
        $this->getElement(Elements::ADMIN_ADI_SETTINGS_COLLAPSE_MENU)->click();
    }

    /**
     * @Given /^I check & setup API Setting "([^"]*)"$/
     * @param string $setting
     * @throws NoSuchElementException
     * @throws UnexpectedTagNameException
     */
    public function iCheckSetupAPISetting($setting) : void
    {
        if (isset(Elements::AMDMIN_API_SETTINGS_VALUES[$setting])) {
            switch (Elements::AMDMIN_API_SETTINGS_VALUES[$setting]['type']) {
                case 'checkbox':
                    $this->configureCheckbox(Elements::AMDMIN_API_SETTINGS_VALUES[$setting]);
                    break;
                case 'input':
                    $this->insertTextInput(Elements::AMDMIN_API_SETTINGS_VALUES[$setting]);
                    break;
                case 'select':
                    $this->selectOption(Elements::AMDMIN_API_SETTINGS_VALUES[$setting]);
                    break;

            }
        }
    }

    /**
     * @param mixed[] $element
     */
    protected function configureCheckbox($element) : void
    {
        $toggle = $element['value']
            ? !$this->getElement($element['selector'])->isSelected()
            : $this->getElement($element['selector'])->isSelected();
        if ($toggle) {
            $this->getElement($element['selector'])->click();
        }
    }

    /**
     * @param mixed[] $element
     */
    protected function insertTextInput($element) : void
    {
        $input = $this->getElement($element['selector']);
        if ($input->getAttribute('value') !== $element['value']) {
            $this->getElement($element['selector'])->sendKeys($element['value']);
        }
    }

    /**
     * @param mixed[] $element
     * @throws UnexpectedTagNameException
     * @throws NoSuchElementException
     */
    protected function selectOption($element) : void
    {
        $select = new WebDriverSelect($this->getElement($element['selector']));
        $select->selectByValue($element ['value']);
    }

    /**
     * @Given /^I save API Settings$/
     */
    public function iSaveAPISettings()
    {
        $this->getElement(Elements::ADMIN_API_SETTINGS_SAVE_BUTTON)->click();
    }

    /**
     * @When /^I click on ingencio collapse menu$/
     */
    public function iClickOnIngencioCollapseMenu()
    {
        self::$driver->navigate()->refresh();
        self::$driver->switchTo()->defaultContent()->switchTo()->frame(Elements::FRAME_MAIN_NAVIGATION)->switchTo()->frame(Elements::FRAME_LEFT_NAVIGATION);
        $collapseMenu = $this->getElement(Elements::ADMIN_INGENICO_NAVIGATION_MENU);
        if ($collapseMenu->getAttribute('class') === '') {
            $collapseMenu->click();
        }
    }

    /**
     * @Given /^I click ingenico settings collapse menu$/
     */
    public function iClickIngenicoSettingsCollapseMenu()
    {
        $this->getElement(Elements::ADMIN_INGENICO_NAVIGATION_MENU_SETTINGS)->click();
    }

    /**
     * @Then /^I can see Ingenico Payments list settings$/
     */
    public function iCanSeeIngenicoPaymentsListSettings()
    {
        self::$driver->switchTo()->defaultContent()->switchTo()->frame(Elements::FRAME_BASIC);
        foreach (Payments::ACTIVATE_PAYMENTS as $payment) {
            WebDriverExpectedCondition::visibilityOf(self::$driver->findElement($this->getSelector("input[value='{$payment}']")));
        }
    }

    /**
     * @When /^I active ingenico Payments$/
     */
    public function iActiveIngenicoPayments()
    {
        self::$driver->switchTo()->defaultContent()->switchTo()->frame(Elements::FRAME_BASIC);
        $checkBoxes = self::$driver->findElements($this->getSelector('input[type="checkbox"]'));
        foreach (Payments::ACTIVATE_PAYMENTS as $payment) {
            $checkBox = self::$driver->findElement($this->getSelector("input[value='{$payment}']"));
            if (!$checkBox->isSelected()) {
                $checkBox->click();
            }
        }
    }

    /**
     * @Given /^I click on update Payment$/
     */
    public function iClickOnUpdatePayment()
    {
        $this->getElement(Elements::ADMIN_INGENICO_NAVIGATION_MENU_SETTINGS_UPDATE_BUTTON)->click();
    }

    /**
     * @Then /^I can see Ingenico Payments are selected$/
     */
    public function iCanSeePaymentsAreSelected()
    {
        self::$driver->switchTo()->defaultContent()->switchTo()->frame(Elements::FRAME_BASIC);
        foreach (Payments::ACTIVATE_PAYMENTS as $payment) {
            WebDriverExpectedCondition::elementToBeSelected(
                $this->getElement("input[value='{$payment}']")
            );
        }
    }

    /**
     * @When /^I click on Shop settings collapse menu$/
     */
    public function iClickOnShopSettingsCollapseMenu()
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_MAIN_NAVIGATION)
            ->switchTo()->frame(Elements::FRAME_LEFT_NAVIGATION);
        $this->getElement(Elements::ADMIN_NAVIGATION_MENU_SHOP_SETTINGS)->click();
    }

    /**
     * @Given /^I click on Shipping Methods collapse menu$/
     */
    public function iClickOnShippingMethodsCollapseMenu()
    {
        $this->getElement(Elements::ADMIN_NAVIGATION_MENU_SHOP_SETTINGS_SHIPPING_METHOD)->click();
    }

    /**
     * @When /^I choose Standard Payment type from Shipping payment list$/
     * @throws Exception
     */
    public function iChooseStandardPaymentTypeFromShippingPaymentList()
    {
        self::$driver->switchTo()->defaultContent()->switchTo()->frame(Elements::FRAME_BASIC)->switchTo()->frame(Elements::FRAME_LIST);
        $this->getElement(Elements::ADMIN_STANDARD_PAYMENT_LIST_ITEM)->click();
        $this->iWaitForThePageToBeLoaded();
    }

    /**
     * @Then /^I am on Main Tab of Standard Shipping Method$/
     */
    public function iAmOnMainTabOfStandardShippingMethod()
    {
        WebDriverExpectedCondition::visibilityOf($this->getElement(Elements::ADMIN_STANDARD_PAYMENT_ACTIVE_STATUS));
    }

    /**
     * @Given /^I access "([^"]*)" Tab Of Standard Shipping Method$/
     * @param string $tab
     */
    public function iAmOnTabOfStandardShippingMethod($tab)
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_BASIC)
            ->switchTo()->frame(Elements::FRAME_LIST);
        $this->getElement(AdminElements::ADMIN_TABS[strtolower($tab)])->click();

    }

    /**
     * @Given /^I click on assign payment methods button$/
     */
    public function iClickOnAssignPaymentMethodsButton()
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_BASIC)
            ->switchTo()->frame(Elements::FRAME_EDIT);
        $this->getElement(Elements::ADMIN_STANDARD_PAYMENT_TAB_ASSIGN_PAYMENT_BUTTON)->click();
    }

    /**
     * @When /^I access "([^"]*)" Dialog Box$/
     */
    public function iAccessAssignPopoutWindow()
    {
        self::$driver->switchTo()->window(self::$driver->getWindowHandles()[1]);
    }

    /**
     * @When /^I click on Assign All button$/
     * @throws Exception
     */
    public function iClickOnAssignAllButton()
    {
        $this->getElement(Elements::ADMIN_ASSIGN_DIALOG_BOX_ASSIGN_BUTTON)->click();
        $this->iWaitForThePageToBeLoaded();
    }

    /**
     * @Then /^I can see the payments on Assigned Payment Methods$/
     */
    public function iCanSeeThePaymentsOnAssignedPaymentMethods()
    {
        WebDriverExpectedCondition::invisibilityOfElementLocated(
            $this->getSelector(Elements::ADMIN_ASSIGN_DIALOG_LEFT_ITEMS_EXISTENCE)
        );
    }

    /**
     * @Given /^I close the popout box$/
     */
    public function iCloseThePopoutBox()
    {
        self::$driver->close();
        self::$driver->switchTo()->window(self::$driver->getWindowHandles()[0]);
    }

    /**
     * @Given /^I am on shop settings collapse menu$/
     */
    public function iAmOnShopSettingsCollapseMenu()
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_MAIN_NAVIGATION)
            ->switchTo()->frame(Elements::FRAME_LEFT_NAVIGATION);
        WebDriverExpectedCondition::visibilityOfElementLocated(
            $this->getSelector(Elements::ADMIN_NAVIGATION_MENU_SHOP_SETTINGS_SELECTED)
        );
    }

    /**
     * @When /^I click on Payments Methods collapse menu$/
     */
    public function iClickOnPaymentsMethodsCollapseMenu()
    {
        $this->getElement(Elements::ADMIN_NAVIGATION_MENU_PAYMENT_METHOD_MENU)->click();
    }

    /**
     * @Then /^I can see that Payments are activated$/
     */
    public function iCanSeeThatPaymentsAreActivated()
    {
        self::$driver->switchTo()->defaultContent()->switchTo()->frame(Elements::FRAME_BASIC)->switchTo()->frame(Elements::FRAME_LIST);
        foreach (Payments::ACTIVATE_PAYMENTS as $payment) {
            $this->getElement($this->buildCssPaymentIdentifierInAdmin($payment));
        }
    }

    /**
     * @param string $payment
     * @return string
     */
    private function buildCssPaymentIdentifierInAdmin(string $payment) : string
    {
        $paymentLink = "Javascript:top.oxid.admin.editThis('" . $payment . "');";
        return '#liste tbody tr td.active +  td a[href="' . $paymentLink . '"]';
    }

    /**
     * @When /^I click on Assign User Group on main Tab of payment$/
     */
    public function iClickOnAssignUserGroupOnMainTabOfPayment()
    {
        self::$driver
            ->switchTo()->defaultContent()
            ->switchTo()->frame(Elements::FRAME_BASIC)
            ->switchTo()->frame(Elements::FRAME_EDIT);
        $this->getElement(Elements::ADMIN_STANDARD_PAYMENT_TAB_ASSIGN_USER_GROUP_BUTTON)->click();
    }

    /**
     * @Then /^All User Group is assigned$/
     * @throws WebDriverException
     */
    public function allUserGroupIsAssigned()
    {
        $data = self::$driver->findElements($this->getSelector(Elements::ADMIN_ASSIGN_DIALOG_LEFT_ITEMS_EXISTENCE));
        if ($data !== []) {
            throw new WebDriverException('Not all User Groups are assigned');
        }
    }

    /**
     * @Given /^I click on Assign Countries button of Main Tab Of Standard Shipping Method$/
     */
    public function iClickOnAssignCountriesButtonOfMainTabOfStandardShippingMethod()
    {

        self::$driver->switchTo()->defaultContent()->switchTo()->frame(Elements::FRAME_BASIC)->switchTo()->frame(Elements::FRAME_EDIT);
        $this->getElement(Elements::ADMIN_STANDARD_PAYMENT_TAB_ASSIGN_COUNTRIES_BUTTON)->click();
    }

    /**
     * @Then /^I can see Assign Countries of the Assigned Countries right box$/
     * @throws WebDriverException
     */
    public function iCanSeeAssignCountriesOfTheAssignedCountriesRightBox()
    {
        WebDriverExpectedCondition::invisibilityOfElementLocated(
            $this->getSelector(Elements::ADMIN_ASSIGN_DIALOG_LEFT_ITEMS_EXISTENCE));
    }

    /**
     * @Given /^I am loggedIn$/
     */
    public function iAmLoggedIn()
    {
        WebDriverExpectedCondition::not(
            WebDriverExpectedCondition::presenceOfElementLocated(
                $this->getSelector(Elements::LOGIN_SHOW_STATUS_CLASS)
            )
        );
    }

    /**
     * @Then /^I click on service menu$/
     */
    public function iClickOnServiceMenu()
    {
        $this->getElement(Elements::MY_ACCOUNT_SERVER_MENU)->click();
    }

    /**
     * @Given /^I click on log out$/
     */
    public function iClickOnLogOut()
    {
        $logOutButton = Elements::MY_ACCOUNT_SERVER_MENU_SUBMIT_BUTTON;
        if ($this->isMobileWidth() || $this->isPlatformMobileDevice()) {
            $logOutButton = Elements::MY_ACCOUNT_SERVER_MENU_SUBMIT_LINK;
        }
        $this->getElement($logOutButton)->click();
    }
}
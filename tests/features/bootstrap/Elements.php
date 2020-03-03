<?php

/**
 * @author  Mediaopt GmbH
 * @author  ibrahim.hilali@mediaopt.de
 */
interface Elements extends AdminElements
{
    /**
     * @var string
     */
    const CHECKOUT_PAYMENTS_PAYMENT_INPUT_IDENTIFICATION = 'input[value=\'{$payment_value}\']';

    /**
     * @var string
     */
    const DIRECTPAY_SIMULATOR_INPUT = 'input[name="DirectEBanking_paymeth"]';

    /**
     * @var string
     */
    const  SIMULATOR_ID = '//div[@id="ACS"]';

    /**
     * @var string
     */
    const SIMULATOR_ACCEPTANCE_BUTTON = '#btn_Accept';

    /**
     * @var string
     */
    const THANKS_MESSAGE_ID = "//div[@id='thankyouPage']";

    /**
     * @var string
     */
    const BASKET_SYMBOL_CLASS = 'svg.shopping-bag';
    /**
     * @var string
     */
    const BASKET_SYMBOL_CONTENT = 'symbol#shoppingBag > text';

    /**
     * @var string
     */
    const  PRODUCTS_BUTTONS = '.productData form button';
    /**
     * @var string
     */
    const BASKET_MODAL_BUTTON = 'div#basketModal div.modal-footer a';

    /**
     * @var string
     */
    const PRODUCTS_IN_BASKET = '#basket_table tbody tr';

    /**
     * @var string
     */
    const  CHECKOUT_CART_PAGE = 'li[class="step1 active "]';

    /**
     * @var string
     */
    const  LOGIN_SHOW_STATUS_CLASS = '.showLogin';
    /**
     * @var string
     */
    const CHECKOUT_NEXT_BUTTON = '.nextStep:first-of-type';

    /**
     * @var string
     */
    const CHECKOUT_USER_NAME_INPUT = 'div[id=content] form[name="login"] input[name=lgn_usr]';

    /**
     * @var string
     */
    const  CHECKOUT_USER_PASSWORD_INPUT = 'div[id=content] form[name="login"] input[name=lgn_pwd]';

    /**
     * @var string
     */
    const CHECKOUT_LOGIN_BUTTON = "div[id=content] form[name='login'] button";

    /**
     * @var string
     */
    const CHECKOUT_LOGIN_FORM = 'div[id=content] form[name=login]';

    /**
     * @var string
     */
    const CHECKOUT_ADDRESS_PAGE_ACTIVE = 'li[class="step2 active "]';

    /**
     * @var string
     */
    const CHECKOUT_PAYMENTS_PAGE_ACTIVE = 'li[class="step3 active "]';

    /**
     * @var string
     */
    const CHECKOUT_CHECK_PAGE_ACTIVE = 'li[class="step4 active "]';

    /**
     * @var string
     */
    const CHECKOUT_PAY_BUTTON = 'form[id="orderConfirmAgbBottom"] button';

    /**
     * @var string
     */
    const MY_ACCOUNT_SERVER_MENU = "div[class*='btn-group service-menu'] > button";

    /**
     * @var string
     */
    const MY_ACCOUNT_SERVER_MENU_SUBMIT_BUTTON = "div[class*='service-menu'] > ul > li a[role='button']";

    /**
     * @var string
     */
    const MY_ACCOUNT_SERVER_MENU_SUBMIT_LINK = "#content a[role='getLogoutLink']";
}
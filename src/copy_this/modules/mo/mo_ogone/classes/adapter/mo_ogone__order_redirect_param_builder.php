<?php

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

class mo_ogone__order_redirect_param_builder extends mo_ogone__request_param_builder
{

    protected $oxidSessionParamsForRemoteCalls;
    protected $billpay_itemNumber = 0;

    // used for redirect payments
    public function build()
    {
        // prepare the payment request parameter
        $params = $this->prepareOrderParams();

        // redirect urls
        $redirectUrl = $this->checkUrlLength(
            $this->getOxConfig()->getSslShopUrl() .
            'index.php?cl=order&fnc=mo_ogone__fncHandleOgoneRedirect', 200);
        $params['accepturl'] = $redirectUrl;
        $params['declineurl'] = $redirectUrl;
        $params['exceptionurl'] = $redirectUrl;
        $params['cancelurl'] = $redirectUrl;
        $params['catalogurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        $params['homeurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        if ($this->getOxConfig()->getConfigParam('ogone_blBackButton') == 'true') {
            $params['backurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        }

        $params['pm'] = Main::getInstance()->getService('OgonePayments')->getPaymentMethodProperty($this->getOxSession()->getBasket()->getPaymentId(), 'pm');

        // if credit card is used and is using redirect (hidden auth is disabled), use the brand that was submitted
        if ($this->getOxSession()->getBasket()->getPaymentId() == 'ogone_credit_card') {
            $dynvalues = $this->getOxSession()->getVariable('dynvalue');
            $params['brand'] = $dynvalues['mo_ogone']['cc']['brand'];
        } else {
            $brand = Main::getInstance()->getService('OgonePayments')->getPaymentMethodProperty($this->getOxSession()->getBasket()->getPaymentId(), 'brand');
            $params['brand'] = is_array($brand) ? $brand[0] : $brand;
        }
        // shop logo
        if ($this->getOxConfig()->getConfigParam('ogone_sTplLogo') !== '') {
            $params['logo'] = $this->getOxConfig()->getConfigParam('ogone_sTplLogo');
        }

        // dynamic template
        if ($this->getOxConfig()->getConfigParam('ogone_sTemplate') == 'true') {
            $params['tp'] = $this->getOxConfig()->getSslShopUrl() . 'index.php?cl=mo_ogone__template&' . http_build_query($this->getOxidSessionParamsForRemoteCalls());

            // static template
        } else {
            $params['title'] = $this->getOxConfig()->getConfigParam('ogone_sTplTitle' . $this->getOxLang()->getBaseLanguage());
            $params['bgcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplBGColor');
            $params['txtcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplFontColor');
            $params['tblbgcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplTableBGColor');
            $params['tbltxtcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplTbFontColor');
            $params['buttonbgcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplBtnBGColor');
            $params['buttontxtcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplBtnFontColor');
            $params['fonttype'] = $this->getOxConfig()->getConfigParam('ogone_sTplFontFamily');
        }

        // billpay extra params
        if ($this->getOxSession()->getBasket()->getPaymentId() == 'ogone_open_invoice_de') {
            $params = $this->addBillPayParams($params);
        }

        // paypal extra params
        if ($this->getOxSession()->getBasket()->getPaymentId() == 'ogone_paypal') {
            $params = $this->addPaypalParams($params);
        }

        $params = $this->handleUtf8Options($params);
        // generates sha signature of request parameter before send
        $params['shasign'] = $this->getShaSignForParams($params);
        foreach ($params as $key => $value) {
            $params[$key] =  htmlspecialchars($value, ENT_QUOTES);
        }
        $this->getLogger()->logExecution($params);

        /* @var $model RequestParameters */
        $model = new RequestParameters();
        $model->setParams($params);

        return $model;
    }

    /*
      [NODOC-Found] ORDERSHIPMETH AN(25) Shipment method
      [NODOC-Found] ORDERSHIPCOST N shipment cost
      [NODOC-Found] ORDERSHIPTAXCODE N shipment tax code
      CIVILITY* AN(10) billing civility (Mr., Mrs, ...)
      [NODOC-Found] ECOM_SHIPTO_POSTAL_NAME_PREFIX AN(10) shipping civility (Mr., Mrs, ...)
      ECOM_SHIPTO_POSTAL_NAME_FIRST AN(50) shipping first name
      ECOM_SHIPTO_POSTAL_NAME_LAST AN(50) shipping last name
      ECOM_SHIPTO_POSTAL_STREET_LINE1 AN(35) shipping address
      ECOM_SHIPTO_POSTAL_STREET_NUMBER AN(10) shipping street number
      ECOM_SHIPTO_POSTAL_POSTALCODE AN(10) shipping zip/postal code
      ECOM_SHIPTO_POSTAL_CITY AN(25) shipping city
      ECOM_SHIPTO_POSTAL_COUNTRYCODE AN(2) shipping country code
      ECOM_SHIPTO_DOB* yyyy-MM-dd Date of birth

      [NODOC-Found] ITEMIDX alphanum(15) item identification (replace X with a number to send multiple items: ITEMID1, ITEMID2, ...)
      [NODOC-Found] ITEMNAMEX alphanum(50) item name (replace X with a number to send multiple items: ITEMNAME1, ITEMNAME2, ...)
      [NODOC-Found] ITEMPRICEX numeric item price (replace X with a number to send multiple items: ITEMPRIC E1, ITEMPRIC E2, ...)
      [NODOC-Found] ITEMQUANTX numeric item quantity (replace X with a number to send multiple items: ITEMQUANT1, ITEMQUANT2, ...)
      [NODOC-Found] ITEMVATX numeric item VAT code (replace X with a number to send multiple items: ITEMVAT1, ITEMVAT2, ...)
     */

    protected function addBillPayParams($params)
    {
        $basket = $this->getOxSession()->getBasket();
        $dynvalues = $this->getOxSession()->getVariable('dynvalue');

        $delivery = oxNew('oxdeliveryset');
        $delivery->load($basket->getShippingId());

        $params['operation'] = 'RES';

        $deliveryPrice = $basket->getCosts('oxdelivery');
        $params['ordershipcost'] = number_format($deliveryPrice->getNettoPrice() * 100, 4); // N net shipment cost multiplied by 100

        $params['ordershiptax'] = number_format($deliveryPrice->getVatValue() * 100, 4); // N shipment tax amount multiplied by 100
        $params['ordershiptaxcode'] = $deliveryPrice->getVat(); // N shipment tax code

        $params['civility'] = $this->getOxLang()->translateString($this->getBillProperty('sal'));


        //bill parameters
        $params['ecom_billto_postal_name_first'] = substr($this->getBillProperty('fname'), 0, 50);
        $params['ecom_billto_postal_name_last'] = substr($this->getBillProperty('lname'), 0, 50);

        //dateformat dd/mm/YYYY
        $date = explode('-', $dynvalues['mo_ogone']['invoice']['birthdate']);
        $params['ecom_shipto_dob'] = $date[2] . '/' . $date[1] . '/' . $date[0];

        //articles
        $params = $this->billpay_addArticles($params);

        //gift-card
        $params = $this->billpay_addGiftCard($params);

        //wrapping
        $params = $this->billpay_addWrapping($params);

        $string = "";
        foreach ($params as $key => $val) {
            $string = $string . $key . ' => ' . $val . "\n";
        }
        $this->getLogger()->info('OrderRedirectParams: ' . $string);

        return $params;
    }

    protected function addPaypalParams($params)
    {
        // If the full name needs to be send to PayPal "firstname + lastname" needs to be sent in one of the fields (ECOM_SHIPTO_POSTAL_NAME_LAST or
        // ECOM_SHIPTO_POSTAL_NAME_FIRST). 
        // If both ECOM_SHIPTO_POSTAL_NAME_ parameters are sent then only the _LAST will be taken into account.
        $paypalFullName = substr($this->getShippingProperty('fname'), 0, 50) . ' ' . substr($this->getShippingProperty('lname'), 0, 50);

        $params['ecom_shipto_postal_name_prefix'] = $this->getOxLang()->translateString($this->getShippingProperty('sal'));
        $params['ecom_shipto_postal_name_first'] = $paypalFullName;
        $params['ecom_shipto_postal_name_last'] = $paypalFullName;
        $params['ecom_shipto_postal_street_line1'] = substr($this->getShippingProperty('street'), 0, 35);
        $params['ecom_shipto_postal_street_number'] = substr($this->getShippingProperty('streetnr'), 0, 10);
        $params['ecom_shipto_postal_postalcode'] = substr($this->getShippingProperty('zip'), 0, 10);
        $params['ecom_shipto_postal_city'] = substr($this->getShippingProperty('city'), 0, 25);
        $params['ecom_shipto_postal_countrycode'] = substr($this->getBillPayDeliveryCountryCode(), 0, 2);
        if ($state = $this->getShippingProperty('stateid')) {
            $params['ecom_shipto_postal_state'] = substr($state, 0, 2);
        }


        return $params;
    }

    protected function billpay_addArticles($params)
    {
        foreach ($this->getOxSession()->getBasket()->getContents() as $item) {
            $price = $item->getUnitPrice();
            $name = $item->getTitle();
            $amount = $item->getAmount();
            $params = array_merge($params, $this->billpay_getItem($name, $price, $amount));
        }
        return $params;
    }

    protected function billpay_addWrapping($params)
    {
        foreach ($this->getOxSession()->getBasket()->getContents() as $item) {
            if (!$wrapping = $item->getWrapping()) {
                continue;
            }

            $price = $wrapping->getWrappingPrice();
            $name = 'Wrapping ' . $item->getTitle();
            $amount = $item->getAmount();
            $params = array_merge($params, $this->billpay_getItem($name, $price, $amount));
        }
        return $params;
    }

    protected function billpay_addGiftCard($params)
    {

        if (!$card = $this->getOxSession()->getBasket()->getCard()) {
            return $params;
        }

        $price = $card->getWrappingPrice();
        $name = 'Card';
        $amount = 1;

        return array_merge($params, $this->billpay_getItem($name, $price, $amount));
    }

    protected function billpay_getItem($name, $price, $amount)
    {
        $this->billpay_itemNumber++;
        $params = array();
        $params['itemid' . $this->billpay_itemNumber] = $this->billpay_itemNumber;
        $params['itemname' . $this->billpay_itemNumber] = substr(trim($name), 0, 40);
        $params['itemprice' . $this->billpay_itemNumber] = number_format($price->getNettoPrice(), 2);
        $params['itemquant' . $this->billpay_itemNumber] = $amount;
        $params['itemvatcode' . $this->billpay_itemNumber] = $price->getVat() . '%';
        $params['facexcl' . $this->billpay_itemNumber] = number_format($price->getNettoPrice() * $amount, 4);
        $params['factotal' . $this->billpay_itemNumber] = number_format($price->getBruttoPrice() * $amount, 4);
        $params['taxincluded' . $this->billpay_itemNumber] = 1;
        return $params;
    }

    protected function getBillPayDeliveryCountryCode()
    {
        $countryid = $this->getShippingProperty('countryid');
        $country = oxNew('oxcountry');
        $country->load($countryid);
        return $country->oxcountry__oxisoalpha2->value;
    }

    protected function getShippingProperty($property)
    {
        $propertyBill = $this->getBillProperty($property);
        $propertyDelivery = $this->getDelProperty($property);

        if (!empty($propertyDelivery)) {
            return $propertyDelivery;
        }
        return $propertyBill;
    }

}

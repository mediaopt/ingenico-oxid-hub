<?php

namespace Mediaopt\Ogone\Adapter\Oxid_5\Factory;

use Mediaopt\Ogone\Sdk\Main;
use Mediaopt\Ogone\Sdk\Model\RequestParameters;

/**
 * $Id: RequestParamBuilder.php 55 2015-02-24 10:47:24Z mbe $ 
 */
class RequestParamBuilder extends AbstractFactory
{

    // mbe: @TODO direkten Zugriff auf oxDB entfernen
    
    protected $oxidSessionParamsForRemoteCalls = null;
    protected $billpay_itemNumber = 0;
    protected $paypal_itemNumber = 0;

    // used for redirect payments
    public function build($order)
    {
        $redirectUrl = $this->checkUrlLength(
                $this->getOxConfig()->getSslShopUrl() .
                // mbe: @TODO remove "&XDEBUG_SESSION_START=netbeans-xdebug"
                'index.php?cl=order&fnc=mo_ogone__fncHandleOgoneRedirect&XDEBUG_SESSION_START=netbeans-xdebug', 200);

        // prepare the payment request parameter
        $requestParams = array(
            'remote_addr' => $_SERVER['REMOTE_ADDR'],
            'pspid' => substr($this->getOxConfig()->getConfigParam('ogone_sPSPID'), 0, 30),
            'orderid' => $order->oxorder__oxordernr->value,
            'amount' => $this->getFormatedOrderAmount(),
            'currency' => \mo_ogone__main::getInstance()->getOgoneConfig()->getOgoneCurrencyCode($order->oxorder__oxcurrency->value),
            'operation' => $this->getCreditCardOperation(),
            // oxidLangCodeToOgoneLanguageCountryCode
            'language' => $this->getLanguageCountryCode(),
            'cn' => substr($order->oxorder__oxbillfname->value . ' ' . $order->oxorder__oxbilllname->value, 0, 35),
            'email' => substr($order->oxorder__oxbillemail->value, 0, 50),
            'ownerzip' => substr($order->oxorder__oxbillzip->value, 0, 10),
            'owneraddress' => substr($order->oxorder__oxbillstreet->value . " " . $order->oxorder__oxbillstreetnr->value, 0, 35),
            'ownercty' => \oxDb::getDB()->getone("select oxisoalpha2 from oxcountry where oxid = '" . $order->oxorder__oxbillcountryid->value . "'"),
            'ownertown' => substr($order->oxorder__oxbillcity->value, 0, 25),
            // redirect urls
            'accepturl' => $redirectUrl,
            'declineurl' => $redirectUrl,
            'exceptionurl' => $redirectUrl,
            'cancelurl' => $redirectUrl,
            'catalogurl' => $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200),
            'homeurl' => $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200),
            'pm' => \mo_ogone__main::getInstance()->getOgoneConfig()->getPaymentMethodProperty($order->oxorder__oxpaymenttype->value, 'pm'),
            'brand' => \mo_ogone__main::getInstance()->getOgoneConfig()->getPaymentMethodProperty($order->oxorder__oxpaymenttype->value, 'brand'),
            //'pmliststyle' => $this->getOxConfig()->getConfigParam( 'ogone_sTplPMListStyle' ),
            'orig' => \mo_ogone__main::getInstance()->getOgoneConfig()->applicationId,
            'paramvar' => substr(http_build_query($this->getOxidSessionParamsForRemoteCalls()), 0, 1000)
        );

        // shop logo
        if ($this->getOxConfig()->getConfigParam('ogone_sTplLogo') != '') {
            $requestParams['logo'] = $this->getOxConfig()->getConfigParam('ogone_sTplLogo');
        }

        // dynamic template
        if ($this->getOxConfig()->getConfigParam('ogone_sTemplate') == 'true') {
            $requestParams['tp'] = $this->getOxConfig()->getSslShopUrl() . 'index.php?cl=mo_ogone__template&' . http_build_query($this->getOxidSessionParamsForRemoteCalls());

            // static template
        } else {
            $requestParams['title'] = $this->getOxConfig()->getConfigParam('ogone_sTplTitle' . $this->getOxLang()->getBaseLanguage());
            $requestParams['bgcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplBGColor');
            $requestParams['txtcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplFontColor');
            $requestParams['tblbgcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplTableBGColor');
            $requestParams['tbltxtcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplTbFontColor');
            $requestParams['buttonbgcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplBtnBGColor');
            $requestParams['buttontxtcolor'] = $this->getOxConfig()->getConfigParam('ogone_sTplBtnFontColor');
            $requestParams['fonttype'] = $this->getOxConfig()->getConfigParam('ogone_sTplFontFamily');
        }

        // backurl
        if ($this->getOxConfig()->getConfigParam('ogone_blBackButton') == 'true') {
            $requestParams['backurl'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        }

        // billpay extra params
        if ($order->oxorder__oxpaymenttype->value == 'ogone_open_invoice_de') {
            $requestParams = $this->addBillPayParams($requestParams, $order);
        }

        // paypal extra params
        if ($order->oxorder__oxpaymenttype->value == 'ogone_paypal') {
            $requestParams = $this->addPaypalParams($requestParams, $order);
        }

        $requestParams['paramplus'] = http_build_query($this->getOxidSessionParamsForRemoteCalls());

        // generates sha signature of request parameter before send
        $requestParams['SHASign'] = $this->getShaSignForParams($requestParams);
        // mbe: @TODO logExecution übernehmen
        //$this->getLogger()->logExecution($requestParams);
        
        /* @var $model RequestParameters */
        $model = $this->getSdkMain()->getModel("RequestParameters");
        $model->setParams($requestParams);
        
        return $model;
    }

    protected function getShaSignForParams($params)
    {
        $signatureBuilder = Main::getInstance()->getService("SignatureBuilder");
        return $signatureBuilder->hash(
                        $signatureBuilder->build(
                                $params, $this->getOxConfig()->getConfigParam('ogone_sSecureKeyIn')));
    }

    protected function getOxidSessionParamsForRemoteCalls()
    {
        if ($this->oxidSessionParamsForRemoteCalls === null) {
            $this->oxidSessionParamsForRemoteCalls = array();
            $this->oxidSessionParamsForRemoteCalls['sess_challenge'] = $this->getOxSession()->getVariable('sess_challenge');
            $this->oxidSessionParamsForRemoteCalls[$this->getOxSession()->getName()] = $this->getOxSession()->getId();
            $this->oxidSessionParamsForRemoteCalls['stoken'] = $this->getOxSession()->getSessionChallengeToken();
            $this->oxidSessionParamsForRemoteCalls['rtoken'] = $this->getOxSession()->getRemoteAccessToken(true);
        }
        return $this->oxidSessionParamsForRemoteCalls;
    }

    public function buildAliasGatewayParams($paymentId)
    {
        $gatewayParams = array();
        $oxViewConf = $this->getOxConfig()->getActiveView()->getViewConfig();

        //build for each paymentoption
        $paymentOptions = \mo_ogone__main::getInstance()->getOgoneConfig()->getOgonePaymentByOxidPaymentId($paymentId);
        foreach ($paymentOptions as $option) {
            if (!$option['active']) {
                continue;
            }
            $params = array();
            $sslSelfLink = str_replace('&amp;', '&', $oxViewConf->getSslSelfLink());

            $params['ACCEPTURL'] = $this->checkUrlLength($sslSelfLink . '&cl=payment&fnc=validatePayment', 200);
            $params['EXCEPTIONURL'] = $this->checkUrlLength($sslSelfLink . '&cl=payment&fnc=validatePayment', 200);
            $params['BRAND'] = $option['brand'];
            $params['PSPID'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');
            $params['PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls()) . '&paymentid=' . $paymentId;

            $params = $this->handleUtf8Options($params);
            $params['SHASIGN'] = $this->getShaSignForParams($params);

            $params['OPERATION'] = $this->getCreditCardOperation();

            $gatewayParams[] = $params;
        }
        // mbe: @TODO logExecution übernehmen
        //$this->getLogger()->logExecution($gatewayParams);
        
        /* @var $model RequestParameters */
        $model = $this->getSdkMain()->getModel("RequestParameters");
        $model->setParams($gatewayParams);
        
        return $model;
    }

    public function buildOnePageOrderdirectParams($order)
    {
        $params = array();
        $params['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        $params['PSPID'] = $this->getOxConfig()->getConfigParam('ogone_sPSPID');
        $params['USERID'] = $this->getOxConfig()->getConfigParam('mo_ogone__api_userid');
        $params['PSWD'] = $this->getApiUserPass();
        $params['ORDERID'] = $order->oxorder__oxordernr->value;
        $params['AMOUNT'] = $this->getFormatedOrderAmount();
        $params['CURRENCY'] = \mo_ogone__main::getInstance()->getOgoneConfig()->getOgoneCurrencyCode($order->oxorder__oxcurrency->value);
        $params['CN'] = substr($order->oxorder__oxbillfname->value . ' ' . $order->oxorder__oxbilllname->value, 0, 35);
        $params['EMAIL'] = substr($order->oxorder__oxbillemail->value, 0, 50);
        $params['OWNERZIP'] = substr($order->oxorder__oxbillzip->value, 0, 10);
        $params['OWNERADDRESS'] = substr($order->oxorder__oxbillstreet->value . " " . $order->oxorder__oxbillstreetnr->value, 0, 35);
        $params['OWNERCTY'] = \oxDb::getDB()->getone("select oxisoalpha2 from oxcountry where oxid = '" . $order->oxorder__oxbillcountryid->value . "'");
        $params['OWNERTOWN'] = substr($order->oxorder__oxbillcity->value, 0, 25);
        $params['OPERATION'] = $this->getCreditCardOperation();
        $params['RTIMEOUT'] = \mo_ogone__main::getInstance()->getOgoneConfig()->rtimeout;

        $params['ORIG'] = \mo_ogone__main::getInstance()->getOgoneConfig()->applicationId;

        $sAliasUsage = '&nbsp;';
        $params['ALIAS'] = $this->getOxSession()->getVariable('mo_ogone__order_alias');
        $params['ALIASUSAGE'] = $sAliasUsage;



        //3D Secure
        $params['FLAG3D'] = 'Y';
        $params['HTTP_ACCEPT'] = $_SERVER['HTTP_ACCEPT'];
        $params['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        $params['WIN3DS'] = 'MAINW';
        $params['ACCEPTURL'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneRedirect', 200);
        $params['DECLINEURL'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneRedirect', 200);
        $params['EXCEPTIONURL'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl() . 'index.php?cl=order' .
                '&fnc=mo_ogone__fncHandleOgoneExceptionFeedbackFrom3dSecureRequest', 200);
        $params['HOMEURL'] = $this->checkUrlLength($this->getOxConfig()->getSslShopUrl(), 200);
        $params['PARAMPLUS'] = http_build_query($this->getOxidSessionParamsForRemoteCalls());
        $params['COMPLUS'] = '';
        $params['LANGUAGE'] = $this->getLanguageCountryCode();

        $params = $this->handleUtf8Options($params);
        $params['SHASIGN'] = $this->getShaSignForParams($params);
        // mbe @TODO logExecution übernehmen
        //$this->getLogger()->logExecution($params);

        /* @var $model RequestParameters */
        $model = $this->getSdkMain()->getModel("RequestParameters");
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

    protected function addBillPayParams($params, $order)
    {
        $basket = $this->getOxSession()->getBasket();
        $dynvalues = $this->getOxSession()->getVariable('dynvalue');

        $delivery = oxNew('oxdeliveryset');
        $delivery->load($order->oxorder__oxdeltype->value);

        $params['operation'] = 'RES';

        $deliveryPrice = $basket->getCosts('oxdelivery');
        $params['ordershipcost'] = number_format($deliveryPrice->getNettoPrice() * 100, 4); // N net shipment cost multiplied by 100

        $params['ordershiptax'] = number_format($deliveryPrice->getVatValue() * 100, 4); // N shipment tax amount multiplied by 100
        $params['ordershiptaxcode'] = $deliveryPrice->getVat(); // N shipment tax code

        $params['civility'] = $this->getOxLang()->translateString($order->oxorder__oxbillsal->value);



        //bill parameters
        $params['ecom_billto_postal_name_first'] = substr($order->oxorder__oxbillfname->value, 0, 50);
        $params['ecom_billto_postal_name_last'] = substr($order->oxorder__oxbilllname->value, 0, 50);

        //dateformat dd/mm/YYYY
        $date = explode('-', $dynvalues['mo_ogone']['invoice']['birthdate']);
        $params['ecom_shipto_dob'] = $date[2] . '/' . $date[1] . '/' . $date[0];

        //articles
        $params = $this->billpay_addArticles($order, $params);

        //gift-card
        $params = $this->billpay_addGiftCard($order, $params);

        //wrapping
        $params = $this->billpay_addWrapping($order, $params);

        return $params;
    }

    protected function addPaypalParams($params, $order)
    {
        // If the full name needs to be send to PayPal "firstname + lastname" needs to be sent in one of the fields (ECOM_SHIPTO_POSTAL_NAME_LAST or
        // ECOM_SHIPTO_POSTAL_NAME_FIRST). 
        // If both ECOM_SHIPTO_POSTAL_NAME_ parameters are sent then only the _LAST will be taken into account.
        $paypalFullName = substr($this->getShippingProperty($order, 'fname'), 0, 50) . ' ' . substr($this->getShippingProperty($order, 'lname'), 0, 50);

        $params['ecom_shipto_postal_name_prefix'] = $this->getOxLang()->translateString($this->getShippingProperty($order, 'sal'));
        $params['ecom_shipto_postal_name_first'] = $paypalFullName;
        $params['ecom_shipto_postal_name_last'] = $paypalFullName;
        $params['ecom_shipto_postal_street_line1'] = substr($this->getShippingProperty($order, 'street'), 0, 35);
        $params['ecom_shipto_postal_street_number'] = substr($this->getShippingProperty($order, 'streetnr'), 0, 10);
        $params['ecom_shipto_postal_postalcode'] = substr($this->getShippingProperty($order, 'zip'), 0, 10);
        $params['ecom_shipto_postal_city'] = substr($this->getShippingProperty($order, 'city'), 0, 25);
        $params['ecom_shipto_postal_countrycode'] = substr($this->getBillPayDeliveryCountryCode($order), 0, 2);


        return $params;
    }

    protected function billpay_addArticles($order, $params)
    {
        foreach ($order->getOrderArticles() as $item) {
            $price = $item->getPrice();
            $name = $item->oxorderarticles__oxtitle->value . ' ' . $item->oxorderarticles__oxselvariant->value;
            $amount = $item->oxorderarticles__oxamount->value;
            $params = array_merge($params, $this->billpay_getItem($name, $price, $amount));
        }
        return $params;
    }

    protected function billpay_addWrapping($order, $params)
    {
        foreach ($order->getOrderArticles() as $item) {
            if (!$wrapping = $item->getWrapping()) {
                continue;
            }

            $price = $wrapping->getWrappingPrice();
            $name = "Wrapping " . $item->oxorderarticles__oxtitle->value . ' ' . $item->oxorderarticles__oxselvariant->value;
            $amount = $item->oxorderarticles__oxamount->value;
            $params = array_merge($params, $this->billpay_getItem($name, $price, $amount));
        }
        return $params;
    }

    protected function billpay_addGiftCard($order, $params)
    {
        if (!$card = $order->getGiftCard()) {
            return $params;
        }

        $price = $card->getWrappingPrice();
        $name = "Card";
        $amount = 1;

        $params = array_merge($params, $this->billpay_getItem($name, $price, $amount));
        return $params;
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

    protected function getShippingProperty($order, $property)
    {
        $propertyBill = 'oxorder__oxbill' . $property;
        $propertyDelivery = 'oxorder__oxdel' . $property;

        if (!empty($order->$propertyDelivery->value)) {
            return $order->$propertyDelivery->value;
        }
        return $order->$propertyBill->value;
    }

    protected function getBillPayDeliveryCountryCode($order)
    {
        $oxid = !empty($order->oxorder__oxdelcountryid->value) ?
                $order->oxorder__oxdelcountryid->value :
                $order->oxorder__oxbillcountryid->value;

        return $this->getCountryCodeById($oxid);
    }

    protected function getCountryCodeById($id)
    {
        return \oxDb::getDB()->getone("select oxisoalpha2 from oxcountry where oxid = '" . $id . "'");
    }

    /**
     * handle utf8 options
     * 
     * @param array $params
     * @return array 
     */
    protected function handleUtf8Options($params)
    {
        if ($this->getOxConfig()->isUtf() && !$this->getOxConfig()->getShopConfVar('mo_ogone__use_utf8')) {
            foreach ($params as $name => $value) {
                $params[$name] = utf8_decode($params[$name]);
            }
        }
        return $params;
    }

    protected function getLanguageCountryCode()
    {
        return \mo_ogone__main::getInstance()->getOgoneConfig()->getLanguageCountryCodeForOxidLanguageCode($this->getOxLang()->getLanguageAbbr());
    }

    protected function getCreditCardOperation()
    {
        return $this->getOxConfig()->getShopConfVar('mo_ogone__capture_creditcard') ? 'RES' : 'SAL';
    }

    protected function getApiUserPass()
    {
        $pass = $this->getOxConfig()->getConfigParam('mo_ogone__api_userpass');
        return $pass;
    }

    protected function checkUrlLength($url, $maxLength)
    {
        if (strlen($url) > $maxLength) {
            $this->getLogger()->error("Url is longer than $maxLength: $url");
        }
        return $url;
    }

    protected function getFormatedOrderAmount()
    {
        $dAmount = $this->getOxSession()->getBasket()->getPrice()->getBruttoPrice();
        $dAmount = number_format($dAmount, 2, '.', '');
        $dAmount = $dAmount * 100;
        $dAmount = round($dAmount, 0);
        $dAmount = substr($dAmount, 0, 15);
        return $dAmount;
    }

}
